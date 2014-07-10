<?php
namespace nGen\Zf2Navigation\NavigationManagement\Mapper;

use nGen\Zf2Entity\Mapper\EntityStatisticsDbMapper;

class NavigationContainerMapper extends EntityStatisticsDbMapper {
	
    protected $tableName = "navigation_container";
           
    public function fetchIdByName($name) {
        $where['name'] = $name;
        $container = $this -> fetchOne($where);
        if($container) return $container -> getId();
        else return false;
    }    
}   