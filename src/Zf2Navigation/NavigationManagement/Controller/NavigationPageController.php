<?php
namespace nGen\Zf2Navigation\NavigationManagement\Controller;

use nGen\Zf2Entity\Controller\EntityStatisticsController;
use nGen\Zf2Navigation\NavigationManagement\Form\NavigationPageForm;
use nGen\Zf2Navigation\NavigationManagement\InputFilter\NavigationPageInputFilter;
use nGen\Zf2Navigation\NavigationManagement\Service\NavigationPageService;
use nGen\Zf2Navigation\NavigationManagement\Service\NavigationContainerService;

class NavigationPageController extends EntityStatisticsController {
	protected $containerService;

	protected $viewData = array(
		"title" => "Menu",
		"pluralTitle" => "Menus",
		'manager' => array(
		    'modes' => array(
		        'active' => array(
					'links' => array(
						"add_submenu" => array(
							'label' => 'Add Submenu',
							'route' => array(
								'name' => array(
									'type' => 'from_view',
									'value' => 'mainRouteName',
								),
								'params' => array(
									'action' => array(
										'type' => 'static',
										'value' => 'add',
									),
									'container' => array(
										'type' => 'from_view',
										'value' => 'container',
									),
									'parent' => array(
										'type' => 'from_record',
										'value' => 'id',									
									),
									'id' => array(
										'type' => 'unset'
									),
								),
							),
						),
						"list_submenu" => array(
							'label' => 'List Submenu',
							'route' => array(
								'name' => array(
									'type' => 'from_view',
									'value' => 'mainRouteName',
								),
								'params' => array(
									'action' => array(
										'type' => 'static',
										'value' => 'index',
									),
									'container' => array(
										'type' => 'from_view',
										'value' => 'container',
									),
									'parent' => array(
										'type' => 'from_record',
										'value' => 'id',									
									),
									'id' => array(
										'type' => 'unset'
									),
								),
							),
						),
					),
				),
			),
		),		
	);

	public function __construct(NavigationPageService $mainService, NavigationContainerService $containerService, $pageRoute, $controllerPage) {
		parent::__construct();
		$this -> mainService = $mainService;
		$this -> containerService = $containerService;

		$this -> viewData['mainRouteName'] = $pageRoute;
		$this -> viewData['parentRouteName'] = $controllerPage;
	}

	protected function init() {
		$viewData = $this -> viewData;
		$container = $this -> params() -> fromRoute('container');
		$container_id = $this -> containerService -> fetchIdByName($container);
		if($container_id === false) {
			$this -> messenger -> addErrorMessage("Invalid {$viewData['title']} \"{$container}\".");
			return $this -> redirectToMain();
		}

		$parent = $this -> params() -> fromRoute('parent', 0);
		$where['menu_id'] = $container_id;
		$where['parent'] = $parent;
		$this -> mainService -> setDefaultWhereRestriction($where);
		return true;
	}	

	protected function indexInit() {
		$viewData = $this -> viewData;

		$container = $this -> params() -> fromRoute('container');
		$container_id = $this -> containerService -> fetchIdByName($container);
		if($container_id === false) {
			$this -> messenger -> addErrorMessage("Invalid {$viewData['title']} \"{$container}\".");
			return $this -> redirectToMain();
		}

		$parent = $this -> params() -> fromRoute('parent', 0);
		if($parent > 0) {
			$parent_row = $this -> mainService -> fetchAsArray($parent);
			$viewData['subTitle'] = 'Parent: '.$parent_row['title']; 
		} else {
			$parent = '0';
		}

		$viewData['route']['params']['parent'] = $parent;
		$viewData['pluralTitle'] = "Menu <small>$container</small>";
		$viewData['container'] = $container;

		$where['menu_id'] = $container_id;
		$where['parent'] = $parent;
		$this -> mainService -> setDefaultWhereRestriction($where);
		$this -> viewData = $viewData;
		return true;
	}

	public function indexAction() {
		$initStatus = $this -> indexInit();
		if($initStatus  !== true) { return $initStatus; }

		return $this -> DefaultIndexAction();
	}	

	public function addAction() {
		$viewData = $this -> viewData;
		$form = new NavigationPageForm();
		$form -> get('submit') -> setValue('Add');
		$request = $this -> getRequest();
		$parent = $this -> params() -> fromRoute('parent');		
		
		$container = $this -> params() -> fromRoute('container');
		$container_id = $this -> containerService -> fetchIdByName($container);
		if($container_id === false) {
			$this -> messenger -> addErrorMessage("Invalid {$viewData['title']} \"{$container}\".");
			return $this -> redirectToMain();
		}

		if($parent > 0) {
			$parent_row = $this -> mainService -> fetchAsArray($parent);
			$viewData['subTitle'] = $parent_row['title']; 
		}
		$viewData['pluralTitle'] = "Menu <small>$container</small>";

		if($request -> isPost()) {
			$navigationPageInputFilter = new NavigationPageInputFilter();
			$form -> setInputFilter($navigationPageInputFilter -> getInputFilter());
			$form -> setData($request -> getPost());

			if($form -> isValid()) {
				$data = $form -> getData();
				$data = array_merge($data, $data['advanced']);
				unset($data['advanced']);
				$data['params'] = json_encode($data['params']);
				$data['properties'] = json_encode($data['properties']);
				$data['added_time'] = date("Y-m-d H:i:s");
				$data['modified_time'] = date("Y-m-d H:i:s");
				$data['parent'] = $parent;
				$data['menu_id'] = $container_id;

				$status = $this -> mainService -> save($data);
				if($status === true) {
					$this -> messenger -> addSuccessMessage($viewData['title']." \"{$data['title']}\" has been added.");
					return $this -> redirect() -> toRoute($viewData['mainRouteName'], array('action' => 'index', 'container' => $this -> params() -> fromRoute('container'), 'parent' => $this -> params() -> fromRoute('parent', 0) ));

				} else {
					$this -> messenger -> addErrorMessage("Errors encountered. Please try again.");
				}
			} else {
				//$message = $form -> getMessages(); var_dump($message);
				$this -> messenger -> addErrorMessage("Errors were encountered in the form you submitted.");
			}
		}
		$viewData['parent'] = $parent;
		$viewData['container'] = $this -> params() -> fromRoute('container');
		$viewData['form'] = $form; 
		$viewData['action'] = "add";
		$viewModel = $this -> getViewModel($viewData);
		$viewModel -> setTemplate("n-gen/navigation-page/form.phtml");
		return $viewModel;
	}
    
    public function editAction() {
    	$viewData = $this -> viewData;
		$form = new NavigationPageForm();
		$form -> get('submit') -> setValue('Add');
		$request = $this -> getRequest();
		$parent = $this -> params() -> fromRoute('parent');		
		
		//Container
		$container = $this -> params() -> fromRoute('container');
		$container_id = $this -> containerService -> fetchIdByName($container);
		if($container_id === false) {
			$this -> messenger -> addErrorMessage("Invalid {$viewData['title']} \"{$container}\".");
			return $this -> redirectToMain();
		}

		//Parent
		if($parent > 0) {
			$parent_row = $this -> mainService -> fetchAsArray($parent);
			$viewData['subTitle'] = $parent_row['title']; 
		}
		$viewData['pluralTitle'] = "Menu <small>$container</small>";

		//Id
        $id = (int) $this -> params() -> fromRoute('id', 0);
        if(!$id) {
            return $this -> redirect() -> toRoute($this -> viewData['mainRouteName'], array(
                'action' => 'add',
                'container' => $this -> params() -> fromRoute('container'), 
                'parent' => $this -> params() -> fromRoute('parent', 0)
            ));
        }

        // Lock Related
        $isLocked = $this -> mainService -> isLocked($id);
        if($isLocked) {
        	if(!$this -> mainService -> isCurrentUserTheLocker($id)) {
        		$this -> messenger -> addErrorMessage("{$this -> viewData['title']} with id: $id is locked. Other user might be updating them.");
        		return $this -> redirectToMain();
        	}
        } else {
        	$this -> mainService -> lock($id);
        }     

        $data = $this -> mainService -> fetchAsArray($id);
        if($data === false) {
			$this -> messenger -> addErrorMessage("{$this -> viewData['title']} with id: $id was not found. It may have already been deleted.");
			return $this -> redirectToMain();
        }

        $temp = array(
        	"id" => $data['id'],
        	"menu_id" => $data['menu_id'],
        	"label" => $data['label'],
        	"title" => $data['title'],
        	"uri" => $data['uri'],
        	"menu" => $data['menu'],
        	"breadcrumbs" => $data['breadcrumbs'],
        	"sitemap" => $data['sitemap'],
    	);
    	unset($data['id'], $data['menu_id'], $data['label'], $data['title'], $data['uri'], $data['menu'], $data['breadcrumbs'], $data['sitemap']);
    	$data['properties'] = json_decode($data['properties'], true);
    	$data['params'] = json_decode($data['params'], true);
    	$data = array_merge($temp, array("advanced" => $data));
    	
    	//Findout if Advanced options was selected
    	if(
    		strlen($data['advanced']['route']) || 
    		strlen($data['advanced']['module']) || 
    		strlen($data['advanced']['controller']) || 
    		strlen($data['advanced']['action']) || 
    		count($data['advanced']['params']) >= 1 || 
    		strlen($data['advanced']['rel']) || 
    		strlen($data['advanced']['rev']) || 
    		strlen($data['advanced']['class']) || 
    		count($data['advanced']['properties']) >= 1
		) { $data['advanced_settings'] = 1; }

		//Form and load the data
        $form = new NavigationPageForm(true);
        $form -> setData($data);
        $form -> get('submit') -> setAttribute('value', "Edit");
        $request = $this -> getRequest();
		
        if($request -> isPost()) {

			$navigationPageInputFilter = new NavigationPageInputFilter(true);
			$form -> setInputFilter($navigationPageInputFilter -> getInputFilter());
			$form -> setData($request -> getPost());
			
			if($form -> isValid()) {
				$form_data = $form -> getData();
				$form_data = array_merge($form_data, $form_data['advanced']);
				unset($form_data['advanced']);
				$form_data['params'] = json_encode($form_data['params']);
				$form_data['properties'] = json_encode($form_data['properties']);
				$form_data['added_time'] = date("Y-m-d H:i:s");
				$form_data['modified_time'] = date("Y-m-d H:i:s");
				$form_data['parent'] = $parent;
				$form_data['menu_id'] = $container_id;

				$status = $this -> mainService -> save($form_data);
				if($status === true) {
					$this -> mainService -> unlock($id);
					$this -> messenger -> addSuccessMessage($viewData['title']." \"{$form_data['title']}\" has been updated.");
					return $this -> redirectToMain();
				} else {
					$this -> messenger -> addErrorMessage("Errors encountered. Please try again.");
				}
			} else {
				$this -> messenger -> addErrorMessage("Errors were encountered in the form you submitted.");
			}
        }
        $viewData['parent'] = $parent;
		$viewData['container'] = $this -> params() -> fromRoute('container');
       	$viewData['id'] = $id;
       	$viewData['form'] = $form;
		$viewData['action'] = "edit";
		$viewModel = $this -> getViewModel($viewData);
		$viewModel -> setTemplate("n-gen/navigation-page/form.phtml");
		return $viewModel;
    }     
}