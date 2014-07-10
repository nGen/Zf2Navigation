<?php
namespace nGen\Zf2Navigation\NavigationManagement\Service;

use nGen\Zfc\Mapper\ExtendedAbstractDbMapper;
use nGen\Zf2Entity\Service\EntityStatisticsService;
use nGen\Zf2Navigation\NavigationManagement\Model\NavigationContainer;

class NavigationContainerService extends EntityStatisticsService implements NavigationContainerServiceInterface {
	protected $dbMapper;
	
	public function __construct (ExtendedAbstractDbMapper $dbMapper) {
		$this -> dbMapper = $dbMapper;		
	}

	public function fetchIdByName($name) {
		return $this -> dbMapper -> fetchIdByName($name);
	}

	public function save(Array $data) {
		return $this -> saveDefault($data, new NavigationContainer());
	}	

}