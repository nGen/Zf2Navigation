<?php
namespace nGen\Zf2Navigation\NavigationManagement\Model;

class NavigationContainer {
	public $id;
	public $name;
	public $status;

	public function setId($v) { $this -> id = $v; }
	public function setName($v) { $this -> name = $v; }
	public function setStatus($v) { $this -> status = $v; }

	public function getId() { return $this -> id; }
	public function getName() { return $this -> name; }
	public function getStatus() { return $this -> status; }
}