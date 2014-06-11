<?php
namespace nGen\Zf2Navigation\NavigationManagement\Mapper\Factory;

use nGen\Zf2Navigation\NavigationManagement\Model\NavigationPage;
use nGen\Zf2Navigation\NavigationManagement\Mapper\NavigationPageMapper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class NavigationPageMapperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $dbAdapter = $serviceLocator -> get('zend_db_adapter');
        $entity = new NavigationPage();
        $mapper = new NavigationPageMapper();
        $mapper -> setDbAdapter($dbAdapter);
        $mapper -> setEntityPrototype($entity);
        $mapper -> setHydrator(new ClassMethods());
        return $mapper;
    }
}