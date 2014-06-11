<?php
namespace nGen\Zf2Navigation\NavigationManagement\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use nGen\Zf2Navigation\NavigationManagement\Form\NavigationContainerForm;
use nGen\Zf2Navigation\NavigationManagement\InputFilter\NavigationContainerInputFilter;
use nGen\Zf2Navigation\NavigationManagement\Service\NavigationContainerService;
use nGen\Zf2Navigation\NavigationManagement\Service\NavigationPageService;
use nGen\Zf2Navigation\NavigationManagement\Service\TripTypeService;

class NavigationContainerController extends AbstractActionController {
	protected $mainService;
	protected $pageService;
	protected $sessionContainer;
	protected $project_base_path;

	protected $viewData = array(
		"title" => "Navigation Menu",
		"pluralTitle" => "Navigation Menus",
	);

	public function __construct(NavigationContainerService $mainService, NavigationPageService $pageService, $project_base_path, $mainRouteName, $subRouteName) {
		$this -> mainService = $mainService;
		$this -> pageService = $pageService;
		$this -> sessionContainer = new Container('navigation');
		$this -> project_base_path = $project_base_path;
		$this -> viewData['mainRouteName'] = $mainRouteName;
		$this -> viewData['subRouteName'] = $subRouteName;
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
		$paginatedEntries = $this -> mainService -> fetchAllPaginated();
		$paginatedEntries -> setCurrentPageNumber((int) $this->params() -> fromQuery('page', 1));
		$paginatedEntries -> setItemCountPerPage(10);
		
		$this -> viewData['rows'] = $paginatedEntries;
		$this -> viewData['msg'] = $this -> getSessionMsg();
		return new ViewModel($this -> viewData);
	}

	public function addAction() {

		$form = new NavigationContainerForm();
		$form -> get('submit') -> setValue('Add');
		$request = $this -> getRequest();
		if($request -> isPost()) {
			$navigationInputFilter = new NavigationContainerInputFilter();
			$form -> setInputFilter($navigationInputFilter -> getInputFilter());
			$form -> setData($request -> getPost());

			if($form -> isValid()) {
				$data = $form -> getData();
				$data['added_time'] = date("Y-m-d H:i:s");
				$data['modified_time'] = date("Y-m-d H:i:s");

				$status = $this -> mainService-> save($data);
				if($status === true) {
					$this -> sessionContainer -> msg = array(
						"type" => "success", 
						"msg" => $this -> viewData['title']." \"{$data['title']}\" has been added."
					);
					return $this -> redirect() -> toRoute($this -> viewData['mainRouteName']);
				} else {
					$this -> viewData['msg'] = array(
						"type" => "danger",
						"msg" => "Errors encountered. Please try again."
					);
				}
			} else {
				$this -> viewData['msg'] = array(
					"type" => "danger",
					"msg" => "Errors were encountered in the form you submitted."
				);
			}
		}
		$this -> viewData['form'] = $form; 
		return new ViewModel($this -> viewData);
	}
    
    public function editAction() {
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
			return $this -> redirect() -> toRoute($this -> viewData['mainRouteName']);        	
        }

        $form = new NavigationContainerForm(true);
        $form -> setData($data);
        $form -> get('submit') -> setAttribute('value', "Edit");
        $request = $this -> getRequest();
		
        if($request -> isPost()) {
			$navigationInputFilter = new NavigationContainerInputFilter(true);
			$form -> setInputFilter($navigationInputFilter -> getInputFilter());
			$form -> setData($request -> getPost());
			
			if($form -> isValid()) {
				$form_data = $form -> getData();
				$form_data['added_time'] = $data['added_time'];
				$form_data['modified_time'] = date("Y-m-d H:i:s");
				
				if(!strlen($form_data['intro_image']) > 0) {
					$form_data['intro_image'] = $data['intro_image'];
				}

				$status = $this -> mainService -> save($form_data);
				if($status === true) {
					$this -> sessionContainer -> msg = array(
						"type" => "success", 
						"msg" => $this -> viewData['title']." \"{$form_data['title']}\" has been updated."
					);
					return $this -> redirect() -> toRoute($this -> viewData['mainRouteName']);
				} else {
					$this -> viewData['msg'] = array(
						"type" => "danger",
						"msg" => "Errors encountered. Please try again."
					);
				}
			} else {
				$this -> viewData['msg'] = array(
					"type" => "danger",
					"msg" => "Errors were encountered in the form you submitted."
				);
			}
        }

       	$this -> viewData['id'] = $id;
       	$this -> viewData['form'] = $form;
        return new ViewModel($this -> viewData);
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

    private function menuRecursor($menu_id, $parent = 0, $depth = 1) {
		$lists = $this -> pageService -> fetchAllAsArray($menu_id, $parent);
		$menu = "";
		$breadcrumbs = "";
		$sitemap = "";

		//Tab Spacing	
		$tabSpaceCount = 2 + $depth;
		$tabSpacing = "";
		$tabPageSpacing = "";
		$tabPageItemSpacing = "";
		for($i = 1; $i <= $tabSpaceCount; $i++) { $tabSpacing .= "\t"; }
		for($i = 1; $i <= $tabSpaceCount + 1; $i++) { $tabPageSpacing .= "\t"; }
		for($i = 1; $i <= $tabSpaceCount + 2; $i++) { $tabPageItemSpacing .= "\t"; }

		foreach($lists as $list) {
			$onMenu = (boolean)$list['menu'];
			$onBreadcrumbs = (boolean)$list['breadcrumbs'];
			$onSitemap = (boolean)$list['sitemap'];
			$list['reset_params'] = (boolean)$list['reset_params'];
			$list['active'] = (boolean)$list['active'];
			$list['visible'] = (boolean)$list['visible'];
			$list['params'] = json_decode($list['params'], true);
			$list['properties'] = json_decode($list['properties'], true);
			$list['order'] = $list['position'];

			//Unneeded fields for menu
			unset($list['menu_id'], $list['menu'], $list['breadcrumbs'], $list['sitemap'], $list['order'], $list['added_time'], $list['modified_time'], $list['parent']);	
			$subLists = $this -> menuRecursor($menu_id, $list['id'], $depth + 1);
			$code = "";
			foreach($list as $key => $value) {
				if(empty($value) || !$value || $value == "_self") {
					continue;
				} elseif(!is_array($value)) {
					if($value === true) { $value = 'true'; }
					elseif($value === false) { $value = 'false'; }
					else { $value = "'$value'"; }
					$code .= (strlen($value) ? "'".$key."' => {$value}, " : '');
				} else {
					if(count($value)) { 
						$code .= "'".$key."' => array(";
						foreach($value as $k => $v) {
							$vValues = array_values($v);
							$code .= "'".$vValues[0]."' => '".$vValues[1]."', ";
						}
						$code .= '), ';
					}
				}
			}

			$tmenu = $code;
			if(strlen($subLists['menu'])) {
				$tmenu .= "'pages' => array(".$subLists['menu']."),";
			}
			$tbreadcrumbs = $code;
			if(strlen($subLists['menu'])) {
				$tbreadcrumbs .= "'pages' => array(".$subLists['breadcrumbs']."),";
			}
			$tsitemap = $code;
			if(strlen($subLists['sitemap'])) {
				$tsitemap .= "'pages' => array(".$subLists['sitemap']."),";
			}

			if($onMenu) $menu .= PHP_EOL.$tabSpacing.'array('.$tmenu.'),';
			if($onBreadcrumbs) $breadcrumbs .= PHP_EOL.$tabSpacing.'array('.$tbreadcrumbs.'),';
			if($onSitemap) $sitemap .= PHP_EOL.$tabSpacing.'array('.$tsitemap.'),';
		}

		return array("menu" => $menu, "breadcrumbs" => $breadcrumbs, "sitemap" => $sitemap);
    }

    public function generateAction() {
    	$file = $this -> project_base_path.'/config/autoload/nav.global.php';

    	$code = "";
		$menus = $this -> mainService -> fetchAllAsArray();
		foreach($menus as $menu) {
			$code_array = $this -> menuRecursor($menu['id']);
			$code .= <<<CODE

		'{$menu['name']}_menu' => array({$code_array['menu']}
		),
		'{$menu['name']}_breadcrumbs' => array({$code_array['breadcrumbs']}
		),
		'{$menu['name']}_sitemap' => array({$code_array['sitemap']}
		),
CODE;

		}
		$menu_code = $code;

		$code = <<<CODE
<?PHP
return array(
	'service_manager' => array(
        'factories' => array(
CODE;
		foreach($menus as $menu) {
			//var_dump($menu); exit;
			$code .= <<<CODE

			'navigation_{$menu['name']}_menu' => function(\$serviceLocator) {
				\$navigation = new nGen\Zf2Navigation\Navigation\Service\NavigationProviderService(\$serviceLocator, '{$menu['name']}_menu');
				return new Zend\Navigation\Navigation(\$navigation -> getPages());
			},
			'navigation_{$menu['name']}_breadcrumbs' => function(\$serviceLocator) {
				\$navigation = new nGen\Zf2Navigation\Navigation\Service\NavigationProviderService(\$serviceLocator, '{$menu['name']}_breadcrumbs');
				return new Zend\Navigation\Navigation(\$navigation -> getPages());
			},
			'navigation_{$menu['name']}_sitemap' => function(\$serviceLocator) {
				\$navigation = new nGen\Zf2Navigation\Navigation\Service\NavigationProviderService(\$serviceLocator, '{$menu['name']}_sitemap');
				return new Zend\Navigation\Navigation(\$navigation -> getPages());
			},
CODE;
		}

		$code .= <<<CODE

        )
    ), //service_manager
    'navigation' => array(
{$menu_code}	
	),
);
CODE;

		$status = file_put_contents($file, $code);
		if($status > 1) {
			$bytes = $status;
			$this -> sessionContainer -> msg = array(
				"type" => "success", 
				"msg" => ceil($bytes / 1024)." KB of Menu Configuration generated."
			);
			return $this -> redirect() -> toRoute($this -> viewData['mainRouteName']);
		} else {
			$this -> sessionContainer -> msg = array(
				"type" => "danger", 
				"msg" => "Error encountered while generating."
			); 
			return $this -> redirect() -> toRoute($this -> viewData['mainRouteName']);			
		}
		
    }
}