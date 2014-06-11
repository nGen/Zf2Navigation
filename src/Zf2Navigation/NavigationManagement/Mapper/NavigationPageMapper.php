<?php
namespace nGen\Zf2Navigation\NavigationManagement\Mapper;

use nGen\Zf2Navigation\NavigationManagement\Model\NavigationPage;

use nGen\Zfc\Mapper\ExtendedAbstractDbMapper;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\DbSelect;


class NavigationPageMapper extends ExtendedAbstractDbMapper {
	
    protected $tableName = "navigation_pages";

    public function fetchAll($paginated = false, $where = array()) {
        $select = $this -> getSelect();
        if(count($where)) {
            $select -> where($where);
        }

        if($paginated) {
            $resultSet = new HydratingResultSet($this->getHydrator(), $this->getEntityPrototype());
            $dbSelect = new DbSelect($select, $this->getDbAdapter(), $resultSet);
            return new Paginator($dbSelect);
        }
        $entity = $this -> select($select);
        return $entity;
    }
           
    public function fetchById($id) {
        $select = $this -> getSelect()
            -> where(array('id' => $id));
        $entity = $this -> select($select) -> current();
        return $entity;
    } 

    public function fetchAllEnabled($paginated = false, $menu_id, $parent) {
        return $this -> fetchAll($paginated, array("status" => true, "menu_id" => $menu_id, "parent" => $parent));
    }

    public function fetchAllDisabled($paginated = false, $menu_id, $parent) {
        return $this -> fetchAll($paginated, array("status" => false, "menu_id" => $menu_id, "parent" => $parent));
    }    
    
    public function delete($id, $where = null, $tableName = null) {
        if (!$where) {
            $where = 'id = ' . $id;
        }
        $result = parent::delete($where, $tableName);
        return $result;
    }

    public function enable($id, $where = null) {
        if (!$where) {
            $where = 'id = ' . $id;
        }
        $result = parent::updateField(array("status" => true), $where, $tableName = null);
        return $result;
    }

    public function disable($id, $where = null) {
        if (!$where) {
            $where = 'id = ' . $id;
        }
        $result = parent::updateField(array("status" => false), $where, $tableName = null);
        return $result;
    }       
	
    public function insertNavigationPage(NavigationPage $entity, $tableName = null, HydratorInterface $hydrator = null) {
        $result = parent::insert($entity, $tableName, $hydrator);
        $entity -> setId($result -> getGeneratedValue());
        return $result;
    }
	public function updateNavigationPage(NavigationPage $entity, $where = null, $tableName = null, HydratorInterface $hydrator = null) {
        if (!$where) {
			$where = 'id = ' . $entity -> getId();
        }

		$result = parent::update($entity, $where, $tableName, $hydrator);
        return $result;
    }     
}   