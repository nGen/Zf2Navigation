<?php
namespace nGen\Zf2Navigation\NavigationManagement\Controller;

use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use nGen\Zf2Navigation\NavigationManagement\Form\NavigationPageForm;
use nGen\Zf2Navigation\NavigationManagement\InputFilter\NavigationPageInputFilter;
use nGen\Zf2Navigation\NavigationManagement\Service\NavigationPageService;
use nGen\Zf2Navigation\NavigationManagement\Service\NavigationContainerService;
use Zend\Mvc\Controller\AbstractActionController;

class NavigationPageController extends AbstractActionController {
	protected $mainService;
	protected $containerService;
	protected $sessionContainer;

	protected $viewData = array(
		"title" => "Menu",
		"pluralTitle" => "Menus",
	);

	public function __construct(NavigationPageService $mainService, NavigationContainerService $containerService, $pageRoute, $controllerPage) {
		$this -> mainService = $mainService;
		$this -> containerService = $containerService;
		$this -> sessionContainer = new Container('navigation');

		$this -> viewData['mainRouteName'] = $pageRoute;
		$this -> viewData['parentRouteName'] = $controllerPage;
	}

	public function getSessionMsg() {
		if(is_array($this -> sessionContainer -> msg)) {
			$msg = $this -> sessionContainer -> msg;
			$this -> sessionContainer -> msg = null; //Remove it
			return $msg;
		}
			return null;
	}

	public function indexAction() {
		$viewData = $this -> viewData;

		$container = $this -> params() -> fromRoute('container');
		$container_id = $this -> containerService -> fetchIdByName($container);
		if($container_id === false) {
			$this -> sessionContainer -> msg = array(
				"type" => "danger", 
				"msg" => "Invalid {$viewData['title']} \"{$container}\"."
			);
			return $this -> redirect() -> toRoute($viewData['parentRouteName'], array('action' => 'index'));			
		}

		$parent = $this -> params() -> fromRoute('parent', 0);
		if($parent > 0) {
			$parent_row = $this -> mainService -> fetchAsArray($parent);
			$viewData['subTitle'] = $parent_row['title']; 
		}
		$viewData['pluralTitle'] = "Menu <small>$container</small>";
		
		if($container) {
			$paginatedEntries = $this -> mainService -> fetchAllPaginated($container_id, $parent);
			$paginatedEntries -> setCurrentPageNumber((int) $this->params() -> fromQuery('page', 1));
			$paginatedEntries -> setItemCountPerPage(10);
			
			$viewData['rows'] = $paginatedEntries;
			$viewData['msg'] = $this -> getSessionMsg();
			$viewData['container'] = $this -> params() -> fromRoute('container');
			
			if($parent >= 0) {
				$viewData['parent'] = $parent;
			}
			return new ViewModel($viewData);
		} else {
			$this -> sessionContainer -> msg = array(
				"type" => "danger", 
				"msg" => "Invalid menu name."
			);
			return $this -> redirect() -> toRoute($viewData['parentRouteName']);			
		}
		
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
			$this -> sessionContainer -> msg = array(
				"type" => "danger", 
				"msg" => "Invalid {$viewData['title']} \"{$container}\"."
			);
			return $this -> redirect() -> toRoute($viewData['parentRouteName'], array('action' => 'index'));			
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
					$this -> sessionContainer -> msg = array(
						"type" => "success", 
						"msg" => $viewData['title']." \"{$data['title']}\" has been added."
					);
					return $this -> redirect() -> toRoute($viewData['mainRouteName'], array('action' => 'index', 'container' => $this -> params() -> fromRoute('container'), 'parent' => $this -> params() -> fromRoute('parent', 0) ));

				} else {
					$viewData['msg'] = array(
						"type" => "danger",
						"msg" => "Errors encountered. Please try again."
					);
				}
			} else {
				$viewData['msg'] = array(
					"type" => "danger",
					"msg" => "Errors were encountered in the form you submitted."
				);
			}
		}
		$viewData['parent'] = $parent;
		$viewData['container'] = $this -> params() -> fromRoute('container');
		$viewData['form'] = $form; 
		$viewData['action'] = "add";
		$viewModel = new ViewModel($viewData);
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
			$this -> sessionContainer -> msg = array(
				"type" => "danger", 
				"msg" => "Invalid {$viewData['title']} \"{$container}\"."
			);
			return $this -> redirect() -> toRoute($viewData['parentRouteName'], array('action' => 'index'));			
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
                'action' => 'add'
            ));
        }

        $data = $this -> mainService -> fetchAsArray($id);
        if($data === false) {
			$this -> sessionContainer -> msg = array(
				"type" => "danger",
				"msg" => "{$this -> viewData['title']} with id: $id was not found. It may have already been deleted."
			);
			return $this -> redirect() -> toRoute($viewData['mainRouteName'], array('action' => 'index', 'container' => $this -> params() -> fromRoute('container'), 'parent' => $this -> params() -> fromRoute('parent', 0) ));
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

//				var_dump($form_data); exit;

				$status = $this -> mainService -> save($form_data);
				if($status === true) {
					$this -> sessionContainer -> msg = array(
						"type" => "success", 
						"msg" => $viewData['title']." \"{$form_data['title']}\" has been updated."
					);
					return $this -> redirect() -> toRoute($viewData['mainRouteName'], array('action' => 'index', 'container' => $this -> params() -> fromRoute('container'), 'parent' => $this -> params() -> fromRoute('parent', 0) ));
				} else {
					$viewData['msg'] = array(
						"type" => "danger",
						"msg" => "Errors encountered. Please try again."
					);
				}
			} else {
				$viewData['msg'] = array(
					"type" => "danger",
					"msg" => "Errors were encountered in the form you submitted."
				);
			}
        }
        $viewData['parent'] = $parent;
		$viewData['container'] = $this -> params() -> fromRoute('container');
       	$viewData['id'] = $id;
       	$viewData['form'] = $form;
		$viewData['action'] = "edit";
		$viewModel = new ViewModel($viewData);
		$viewModel -> setTemplate("n-gen/navigation-page/form.phtml");
		return $viewModel;
    }

	public function deleteAction() {
    	$id = (int) $this -> params() -> fromRoute('id', 0);
        if($id > 0 && $this -> mainService -> fetch($id) !== false) {
    		$response = $this -> mainService -> delete($id);
    		if($response === true) {
				$this -> sessionContainer -> msg = array(
					"type" => "success", 
					"msg" => $this -> viewData['title']." id: $id has been deleted."
				); 
			} else {
				$this -> sessionContainer -> msg = array(
					"type" => "danger",
					"msg" => "Error encountered while deleting {$this -> viewData['title']} with id: $id."
				);
			}
		} else {
			$this -> sessionContainer -> msg = array(
				"type" => "danger",
				"msg" => "{$this -> viewData['title']} with id: $id was not found. It may have already been deleted."
			);
		}
		return $this -> redirect() -> toRoute($this -> viewData['mainRouteName']);
    }

public function enableAction() {
    	$id = (int) $this -> params() -> fromRoute('id', 0);
        if($id > 0 && $this -> mainService -> fetch($id) !== false) {
    		$response = $this -> mainService -> enable($id);
    		if($response === true) {
				$this -> sessionContainer -> msg = array(
					"type" => "success", 
					"msg" => $this -> viewData['title']." id: $id has been enabled."
				); 
			} else {
				$this -> sessionContainer -> msg = array(
					"type" => "danger",
					"msg" => "Error encountered while enabling {$this -> viewData['title']} with id: $id."
				);
			}
		} else {
			$this -> sessionContainer -> msg = array(
				"type" => "danger",
				"msg" => "{$this -> viewData['title']} with id: $id was not found. It may have already been deleted."
			);
		}
		return $this -> redirect() -> toRoute($this -> viewData['mainRouteName']);
    }
    
    public function disableAction() {
    	$id = (int) $this -> params() -> fromRoute('id', 0);
        if($id > 0 && $this -> mainService -> fetch($id) !== false) {
    		$response = $this -> mainService -> disable($id);
    		if($response === true) {
				$this -> sessionContainer -> msg = array(
					"type" => "success", 
					"msg" => $this -> viewData['title']." id: $id has been disabled."
				); 
			} else {
				$this -> sessionContainer -> msg = array(
					"type" => "danger",
					"msg" => "Error encountered while disabling {$this -> viewData['title']} with id: $id."
				);
			}
		} else {
			$this -> sessionContainer -> msg = array(
				"type" => "danger",
				"msg" => "{$this -> viewData['title']} with id: $id was not found. It may have already been deleted."
			);
		}
		return $this -> redirect() -> toRoute($this -> viewData['mainRouteName']);
    }        
}