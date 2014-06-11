<?php
namespace nGen\Zf2Navigation\NavigationManagement\Controller\Factory;

use nGen\Zf2Navigation\NavigationManagement\Controller\NavigationPageController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class NavigationPageControllerFactory implements FactoryInterface
{	
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $realServiceLocator = $serviceLocator->getServiceLocator();
        $navigationPageService = $realServiceLocator->get('nGenZf2NavigationPageService');
        $navigationContainerService = $realServiceLocator->get('nGenZf2NavigationContainerService');        
    	$config = $realServiceLocator -> get('Config');
        $controllerRoute = $config['Zf2NavigationControllerRouteName'];
        $pageRoute = $config['Zf2NavigationPageRouteName'];
        return new NavigationPageController($navigationPageService, $navigationContainerService, $pageRoute, $controllerRoute);
    }
}