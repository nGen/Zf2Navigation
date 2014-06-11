<?php
namespace nGen\Zf2Navigation\NavigationManagement\Model;

class NavigationPage {
	public $id;
	public $menu_id;
	public $label;
	public $title;
	public $uri;
	public $menu;
	public $breadcrumbs;
	public $sitemap;
	public $route;
	public $module;
	public $controller;
	public $action;
	public $params;
	public $reset_params;
	public $active;
	public $visible;
	public $target;
	public $rel;
	public $rev;
	public $class;
	public $properties;
	public $position;
	public $parent;
	public $added_time;
	public $modified_time;

	public function setId($v) { $this -> id = $v; }
	public function setMenuId($v) { $this -> menu_id = $v; }
	public function setLabel($v) { $this -> label = $v; }
	public function setTitle($v) { $this -> title = $v; }
	public function setUri($v) { $this -> uri = $v; }
	public function setMenu($v) { $this -> menu = $v; }
	public function setBreadcrumbs($v) { $this -> breadcrumbs = $v; }
	public function setSitemap($v) { $this -> sitemap = $v; }
	public function setRoute($v) { $this -> route = $v; }
	public function setModule($v) { $this -> module = $v; }
	public function setController($v) { $this -> controller = $v; }
	public function setAction($v) { $this -> action = $v; }
	public function setParams($v) { $this -> params = $v; }
	public function setResetParams($v) { $this -> reset_params = $v; }
	public function setActive($v) { $this -> active = $v; }
	public function setVisible($v) { $this -> visible = $v; }
	public function setTarget($v) { $this -> target = $v; }
	public function setRel($v) { $this -> rel = $v; }
	public function setRev($v) { $this -> rev = $v; }
	public function setClass($v) { $this -> class = $v; }
	public function setProperties($v) { $this -> properties = $v; }
	public function setPosition($v) { $this -> position = $v; }
	public function setParent($v) { $this -> parent = $v; }
	public function setAddedTime($v) { $this -> added_time = $v; }
	public function setModifiedTime($v) { $this -> modified_time = $v; }

	public function getId() { return $this -> id; }
	public function getMenuId() { return $this -> menu_id; }
	public function getLabel() { return $this -> label; }
	public function getTitle() { return $this -> title; }
	public function getUri() { return $this -> uri; }
	public function getMenu() { return $this -> menu; }
	public function getBreadcrumbs() { return $this -> breadcrumbs; }
	public function getSitemap() { return $this -> sitemap; }
	public function getRoute() { return $this -> route; }
	public function getModule() { return $this -> module; }
	public function getController() { return $this -> controller; }
	public function getAction() { return $this -> action; }
	public function getParams() { return $this -> params; }
	public function getResetParams() { return $this -> reset_params; }
	public function getActive() { return $this -> active; }
	public function getVisible() { return $this -> visible; }
	public function getTarget() { return $this -> target; }
	public function getRel() { return $this -> rel; }
	public function getRev() { return $this -> rev; }
	public function getClass() { return $this -> class; }
	public function getProperties() { return $this -> properties; }
	public function getPosition() { return $this -> position; }
	public function getParent() { return $this -> parent; }
	public function getAddedTime() { return $this -> added_time; }
	public function getModifiedTime() { return $this -> modified_time; }
}