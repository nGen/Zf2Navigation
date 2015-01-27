<?php
namespace nGen\Zf2Navigation\NavigationManagement\Controller;

use nGen\Zf2Entity\Controller\EntityStatisticsController;
use nGen\Zf2Navigation\NavigationManagement\Form\NavigationContainerForm;
use nGen\Zf2Navigation\NavigationManagement\InputFilter\NavigationContainerInputFilter;
use nGen\Zf2Navigation\NavigationManagement\Service\NavigationContainerService;
use nGen\Zf2Navigation\NavigationManagement\Service\NavigationPageService;

class NavigationContainerController extends EntityStatisticsController {
	protected $pageService;
	protected $project_base_path;

	protected $viewData = array(
		"title" => "Navigation Menu",
		"pluralTitle" => "Navigation Menus",
		'manager' => array(
			'title_field' => array(
				'active' => true,
				'details' => array(
					'label' => 'Menu',
					'field' => 'name'
				),
			),
		    'modes' => array(
		        'active' => array(
					'links' => array(
						'manage-links' => array(
							'label' => 'Manage Links',
							'route' => array(
								'name' => array('type' => 'from_view', 'value' => 'subRouteName',),
								'params' => array(
									'action' => array('type' => 'static', 'value' => 'index',),
									'container' => array('type' => 'from_record', 'value' => 'name',),
									'parent' => array('type' => 'static', 'value' => '0',),
									'id' => array('type' => 'unset'),
								),
							),
						),
						'add-links' => array(
							'label' => 'Add Links',
							'route' => array(
								'name' => array('type' => 'from_view', 'value' => 'subRouteName',),
								'params' => array(
									'action' => array('type' => 'static', 'value' => 'add',),
									'container' => array('type' => 'from_record', 'value' => 'name',),
									'parent' => array('type' => 'static', 'value' => '0',),
									'id' => array('type' => 'unset'),
								),
							),
						),
					),
				),
			),
		),		
	);

	public function __construct(NavigationContainerService $mainService, NavigationPageService $pageService, $mainRouteName, $subRouteName) {
		parent::__construct();
		$this -> mainService = $mainService;
		$this -> pageService = $pageService;
		$this -> viewData['mainRouteName'] = $mainRouteName;
		$this -> viewData['subRouteName'] = $subRouteName;
	}

	protected function init() { return true; }

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

				$status = $this -> mainService-> save($data);
				if($status === true) {
					$this -> messenger -> addSuccessMessage($this -> viewData['title']." \"{$data['name']}\" has been added.");
					return $this -> redirectToMain();
				} else {
					$this -> messenger -> addErrorMessage("Errors encountered. Please try again.");
				}
			} else {
				$this -> messenger -> addErrorMessage("Errors were encountered in the form you submitted.");
			}
		}
		$this -> viewData['form'] = $form; 
		return $this -> getViewModel();
	}
    
    public function editAction() {
        $id = (int) $this -> params() -> fromRoute('id', 0);
        if(!$id) {
            return $this -> redirect() -> toRoute($this -> viewData['mainRouteName'], array(
                'action' => 'add'
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

				$status = $this -> mainService -> save($form_data);
				if($status === true) {
					$this -> mainService -> unlock($id);
					$this -> messenger -> addSuccessMessage($this -> viewData['title']." \"{$form_data['name']}\" has been updated.");
					return $this -> redirectToMain();
				} else {
					$this -> messenger -> addErrorMessage("Errors encountered. Please try again.");
				}
			} else {
				$this -> messenger -> addErrorMessage("Errors were encountered in the form you submitted.");
			}
        }

       	$this -> viewData['id'] = $id;
       	$this -> viewData['form'] = $form;
        return $this -> getViewModel();
    }

    private function menuRecursor($menu_id, $parent = 0, $depth = 1) {
    	$where['menu_id'] = $menu_id;
    	$where['parent'] = $parent;
		$lists = $this -> pageService -> fetchAllActiveAsArray($where);
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
			$list['active'] = (boolean)$list['status'];
			$list['visible'] = (boolean)$list['visible'];
			$list['params'] = json_decode($list['params'], true);
			$list['properties'] = json_decode($list['properties'], true);
			$list['order'] = $list['ordering'];

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
			if(strlen($subLists['breadcrumbs'])) {
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
    	//Check if global configuration file is writable
    	$global_file = './config/autoload/nav.global.php';
    	if(!is_writable($global_file)) {
    		$this -> messenger -> addErrorMessage("Navigation configuartion file \"config/autoload/nav.global.php\" is not writable."); 
    		return $this -> redirectToMain();
    	}

    	//Check if the navigation directory is writable
		$directory = './config/navigation';
    	if(!is_writable($directory)) {
    		$this -> messenger -> addErrorMessage("Navigation configuartion directory \"config/navigation\" is not writable."); 
    		return $this -> redirectToMain();
    	}    	

    	$global_menu_config = "";
		$menus = $this -> mainService -> fetchAllActiveAsArray();
		foreach($menus as $menu) {
			$code_array = $this -> menuRecursor($menu['id']);
			if($menu['separate_config'] === '1') {
				foreach($code_array as $config_key => $configuration) {
					$separate_config_file = $directory."/".$menu['name']."_".$config_key.".php";
					$separate_config_code = <<<CODE
<?PHP					
return array(
	{$configuration}
);
CODE;
					$status = file_put_contents($separate_config_file, $separate_config_code);
					if($status > 1) {
						$this -> messenger -> addSuccessMessage(ucwords($config_key)." configuration file of \"{$menu['name']}\" was generated successfully.");
					} else {
						$this -> messenger -> addErrorMessage("Error encountered while generating ".ucwords($config_key)." configuration file of \"{$menu['name']}\"."); 			
					}
				}
			} else {				
				$global_menu_config .= <<<CODE

			'{$menu['name']}_menu' => array({$code_array['menu']}
			),
			'{$menu['name']}_breadcrumbs' => array({$code_array['breadcrumbs']}
			),
			'{$menu['name']}_sitemap' => array({$code_array['sitemap']}
			),
CODE;
			}
		}

		$global_code = <<<CODE
<?PHP
return array(
	'service_manager' => array(
        'factories' => array(
CODE;
		foreach($menus as $menu) {
			//static list of menu
			$menuType = array('menu', 'breadcrumbs', 'sitemap');
			foreach($menuType as $type) {
				if($menu['separate_config'] === '1') {
					$global_code .= <<<CODE

			'navigation_{$menu['name']}_$type' => function(\$serviceLocator) {
				\$navigation = new nGen\Zf2Navigation\Navigation\Service\SeparatedNavigationProviderService(\$serviceLocator, '{$menu['name']}', '$type');
				return new Zend\Navigation\Navigation(\$navigation -> getPages());
			},
CODE;
				} else {
					$global_code .= <<<CODE

			'navigation_{$menu['name']}_$type' => function(\$serviceLocator) {
				\$navigation = new nGen\Zf2Navigation\Navigation\Service\NavigationProviderService(\$serviceLocator, '{$menu['name']}_$type');
				return new Zend\Navigation\Navigation(\$navigation -> getPages());
			},
CODE;
				}	
			}
		}

		$global_code .= <<<CODE

        )
    ), //service_manager
    'navigation' => array(
{$global_menu_config}	
	),
);
CODE;

		$status = file_put_contents($global_file, $global_code);
		if($status > 1) {
			$bytes = $status;
			$this -> messenger -> addSuccessMessage(ceil($bytes / 1024)." KB of Global Menu Configuration generated.");
		} else {
			$this -> messenger -> addErrorMessage("Error encountered while generating the global configuration."); 			
		}
		return $this -> redirectToMain();
		
    }
}