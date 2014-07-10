<?php
namespace nGen\Zf2Navigation\NavigationManagement\Model;

use nGen\Zf2Entity\Model\SharedEntityStatistics;

class NavigationContainer extends SharedEntityStatistics {
	public $id;
	public $name;

	public function setId($v) { $this -> id = $v; }
	public function setName($v) { $this -> name = $v; }

	public function getId() { return $this -> id; }
	public function getName() { return $this -> name; }
}