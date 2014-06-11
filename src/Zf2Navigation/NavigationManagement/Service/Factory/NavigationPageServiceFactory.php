<?php
namespace nGen\Zf2Navigation\NavigationManagement\Service\Factory;

use nGen\Zf2Navigation\NavigationManagement\Service\NavigationPageService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class NavigationPageServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) {
        return new NavigationPageService($serviceLocator->get('nGenZf2NavigationPageMapper'));
    }
}