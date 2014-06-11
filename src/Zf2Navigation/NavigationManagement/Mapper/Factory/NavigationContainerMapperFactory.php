<?php
namespace nGen\Zf2Navigation\NavigationManagement\Mapper\Factory;

use nGen\Zf2Navigation\NavigationManagement\Model\NavigationContainerPage;
use nGen\Zf2Navigation\NavigationManagement\Model\NavigationContainer;
use nGen\Zf2Navigation\NavigationManagement\Mapper\NavigationContainerMapper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class NavigationContainerMapperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $dbAdapter = $serviceLocator -> get('zend_db_adapter');
        $entity = new NavigationContainer();
        $mapper = new NavigationContainerMapper();
        $mapper -> setDbAdapter($dbAdapter);
        $mapper -> setEntityPrototype($entity);
        $mapper -> setHydrator(new \nGen\Zf2Navigation\NavigationManagement\Hydrator\StandardHydrator());
        return $mapper;
    }
}