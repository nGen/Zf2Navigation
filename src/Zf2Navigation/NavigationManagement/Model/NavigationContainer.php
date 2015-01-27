<?php
namespace nGen\Zf2Navigation\NavigationManagement\Model;

use nGen\Zf2Entity\Model\SharedEntityStatistics;

class NavigationContainer extends SharedEntityStatistics {
	public $id;
	public $name;
	public $separate_config;

	public function setId($v) { $this -> id = $v; }
	public function setName($v) { $this -> name = $v; }
	public function setSeparateConfig($v) { $this -> separate_config = $v; }

	public function getId() { return $this -> id; }
	public function getName() { return $this -> name; }
	public function getSeparateConfig() { return $this -> separate_config; }
}