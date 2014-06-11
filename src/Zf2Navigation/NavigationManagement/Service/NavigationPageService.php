<?php
namespace nGen\Zf2Navigation\NavigationManagement\Service;
use nGen\Zfc\Mapper\ExtendedAbstractDbMapper;
use nGen\Zf2Navigation\NavigationManagement\Model\NavigationPage;

class NavigationPageService implements NavigationPageServiceInterface {
	protected $dbMapper;
	
	public function __construct (ExtendedAbstractDbMapper $dbMapper) {
		$this -> dbMapper = $dbMapper;		
	}

	public function fetch($id) {
		if((int) $id) {
			return $this -> dbMapper -> fetchById($id);
		}
		return false;
	}
	
	public function fetchAsArray($id) {
		$entity = $this -> fetch($id);
		if($entity !== false) {
			return $this -> dbMapper -> getHydrator() -> extract($entity);
		}
		return false;
	}

	public function fetchAll($menu_id, $parent) {
		return $this -> dbMapper -> fetchAll(false, array("menu_id" => $menu_id, "parent" => $parent));
	}

	public function fetchAllAsArray($menu_id, $parent) {
		$entities_array = array();
		$entities = $this -> fetchAll($menu_id, $parent);
		foreach($entities as $entity) {
			$entities_array[] = $this -> dbMapper -> getHydrator() -> extract($entity);
		}
		return $entities_array;
	}	
	
	public function fetchAllPaginated($menu_id, $parent) {
		return $this -> dbMapper -> fetchAll(true, array("menu_id" => $menu_id, "parent" => $parent));	
	}

	public function fetchAllEnabled($menu_id, $parent) {
		return $this -> dbMapper -> fetchAllEnabled(false, $menu_id, $parent);
	}
	
	public function fetchAllEnabledPaginated($parent) {
		return $this -> dbMapper -> fetchAllEnabled(true, $menu_id, $parent);
	}
	
	public function fetchAllDisabled($parent) {
		return $this -> dbMapper -> fetchAllDisabled(false, $menu_id, $parent);
	}
	
	public function fetchAllDisabledPaginated($parent) {
		return $this -> dbMapper -> fetchAllDisabled(true, $menu_id, $parent);
	}

	public function save(Array $data) {
		$this -> dbMapper -> beginTransaction();					
		
		try {
			if($data['id'] > 0) { $editMode = true; }

			$data['active'] = true;
			$data['position'] = 1;

			$navigationPage = $this -> dbMapper
				-> getHydrator()
				-> hydrate($data, new NavigationPage());
			if(isset($editMode) && $editMode === true) {
				$result = $this -> dbMapper -> updateNavigationPage($navigationPage);
			} else { 
				$result = $this -> dbMapper -> insertNavigationPage($navigationPage);
			}
			$this -> dbMapper -> commit();
			return true;
			
		} catch(\Zend\Db\Adapter\Exception\InvalidQueryException $e) {
			$this -> dbMapper -> rollBack();
			return false;
		}
	}

	public function enable($id) {
		try {
			$this -> dbMapper -> enable($id);
			return true;
		} catch(\Exception $e) {
			return false;
		}
	}
	
	public function disable($id) {
		try {
			$this -> dbMapper -> disable($id);
			return true;
		} catch(\Exception $e) {
			return false;
		}
	}	

	public function delete($id) {
		try {
			$this -> dbMapper -> delete($id);
			return true;
		} catch(\Exception $e) {
			return false;
		}
	}
}