<?php
namespace nGen\Zf2Navigation\NavigationManagement\Service\Factory;

use nGen\Zf2Navigation\NavigationManagement\Service\NavigationContainerService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class NavigationContainerServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) {
        return new NavigationContainerService($serviceLocator->get('nGenZf2NavigationContainerMapper'));
    }
}