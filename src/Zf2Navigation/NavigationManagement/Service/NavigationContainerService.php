<?php
namespace nGen\Zf2Navigation\NavigationManagement\Service;

use nGen\Zfc\Mapper\ExtendedAbstractDbMapper;
use nGen\Zf2Navigation\NavigationManagement\Model\NavigationContainer;

class NavigationContainerService implements NavigationContainerServiceInterface {
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

	public function fetchIdByName($name) {
		return $this -> dbMapper -> fetchIdByName($name);
	}
	
	public function fetchAsArray($id) {
		$entity = $this -> fetch($id);
		if($entity !== false) {
			return $this -> dbMapper -> getHydrator() -> extract($entity);
		}
		return false;
	}

	public function fetchAll() {
		return $this -> dbMapper -> fetchAll();
	}

	public function fetchAllAsArray() {
		$entities_array = array();
		$entities = $this -> fetchAll();
		foreach($entities as $entity) {
			$entities_array[] = $this -> dbMapper -> getHydrator() -> extract($entity);
		}
		return $entities_array;
	}	
	
	public function fetchAllPaginated() {
		return $this -> dbMapper -> fetchAll(true);	
	}

	public function fetchAllEnabled() {
		return $this -> dbMapper -> fetchAllEnabled();
	}
	
	public function fetchAllEnabledPaginated() {
		return $this -> dbMapper -> fetchAllEnabled(true);
	}
	
	public function fetchAllDisabled() {
		return $this -> dbMapper -> fetchAllDisabled();
	}
	
	public function fetchAllDisabledPaginated() {
		return $this -> dbMapper -> fetchAllDisabled(true);
	}	

	public function save(Array $data) {
		$this -> dbMapper -> beginTransaction();					
		
		try {
			if($data['id'] > 0) { $editMode = true; }

			$data['status'] = true;
			$data['position'] = 1;

			$navigation = $this -> dbMapper
				-> getHydrator()
				-> hydrate($data, new NavigationContainer());
			if(isset($editMode) && $editMode === true) {
				$result = $this -> dbMapper -> updateNavigationContainer($navigation);
			} else { 
				$result = $this -> dbMapper -> insertNavigationContainer($navigation);
			}
			$this -> dbMapper -> commit();
			return true;
			
		} catch(\Zend\Db\Adapter\Exception\InvalidQueryException $e) {
			$this -> dbMapper -> rollBack();
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
}