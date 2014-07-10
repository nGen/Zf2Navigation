<?php
namespace nGen\Zf2Navigation\NavigationManagement\Service;

use nGen\Zfc\Mapper\ExtendedAbstractDbMapper;
use nGen\Zf2Entity\Service\EntityStatisticsService;
use nGen\Zf2Navigation\NavigationManagement\Model\NavigationPage;

class NavigationPageService extends EntityStatisticsService implements NavigationPageServiceInterface {
	protected $dbMapper;
	
	public function __construct (ExtendedAbstractDbMapper $dbMapper) {
		$this -> dbMapper = $dbMapper;
	}

	public function save(Array $data) {
		return $this -> saveDefault($data, new NavigationPage());
	}

}