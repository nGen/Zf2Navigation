<?php
namespace nGen\Zf2Navigation\NavigationManagement\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;  
use Zend\ServiceManager\ServiceLocatorInterface;

class NgenNavigation extends AbstractHelper implements ServiceLocatorAwareInterface
{
    /** 
     * Set the service locator. 
     * 
     * @param ServiceLocatorInterface $serviceLocator 
     * @return Gallery Helper 
     */  
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {  
        $this->serviceLocator = $serviceLocator;  
        return $this;  
    }

    /** 
     * Get the service locator. 
     * 
     * @return \Zend\ServiceManager\ServiceLocatorInterface 
     */  
    public function getServiceLocator() {  
        return $this->serviceLocator;  
    }

    public function getRealServiceLocator() {
    	return $this -> getServiceLocator() -> getServiceLocator();
    }

    public function __invoke($menu_type, $menu_name) {
        $file = './config/navigation/'.$menu_name.'_'.$menu_type.'.php';
        $config = include($file);
        $container = new \Zend\Navigation\Navigation($config);
        return $container;
    }
}