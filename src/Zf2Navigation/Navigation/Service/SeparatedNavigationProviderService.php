<?php

namespace nGen\Zf2Navigation\Navigation\Service;

use Zend\Config;
use Zend\Http\Request;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\RouteStackInterface as Router;
use Zend\Navigation\Exception;
use Zend\Navigation\Navigation;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Navigation Provider Service
 */
class SeparatedNavigationProviderService
{
    /**
     * @var array
     */
    protected $pages;
    protected $name;
    protected $type;
    protected $serviceLocator;

    function __construct(ServiceLocatorInterface $serviceLocator, $name, $type) {
        $this -> serviceLocator = $serviceLocator;
        $this -> name = $name;
        $this -> type = $type;
    }

    /**
     * @abstract
     * @return string
     */
    protected function getName() {
        return $this -> name;
    }

    /**
     * Set the container Name
     * @param String $name [description]
     */
    public function setName($name) {
        $this -> name = $name;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return array
     * @throws \Zend\Navigation\Exception\InvalidArgumentException
     */
    public function getPages()
    {

        if (null === $this->pages) {
            $file = './config/navigation/'.$this -> name.'_'.$this -> type.'.php';
            if(!is_readable(($file))) {
                throw new Exception\InvalidArgumentException(sprintf(
                    'Failed to find a navigation configuration file by the name "%s"',
                    $this->getName()
                ));
            }
            $configuration = include($file);
            if(!is_array($configuration)) {
                throw new Exception\InvalidArgumentException(sprintf(
                    'Invalid configuration for the navigation name "%s"',
                    $this->getName()
                ));   
            }
            $pages       = $this->getPagesFromConfig($configuration);
            $this->pages = $this->preparePages($this -> serviceLocator, $pages);
        }
        return $this->pages;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param array|\Zend\Config\Config $pages
     * @throws \Zend\Navigation\Exception\InvalidArgumentException
     */
    protected function preparePages(ServiceLocatorInterface $serviceLocator, $pages)
    {
        $application = $serviceLocator->get('Application');
        $routeMatch  = $application->getMvcEvent()->getRouteMatch();
        $router      = $application->getMvcEvent()->getRouter();
        $request     = $application->getMvcEvent()->getRequest();

        // HTTP request is the only one that may be injected
        if (!$request instanceof Request) {
            $request = null;
        }

        return $this->injectComponents($pages, $routeMatch, $router, $request);
    }

    /**
     * @param string|\Zend\Config\Config|array $config
     * @return array|null|\Zend\Config\Config
     * @throws \Zend\Navigation\Exception\InvalidArgumentException
     */
    protected function getPagesFromConfig($config = null)
    {
        if (is_string($config)) {
            if (file_exists($config)) {
                $config = Config\Factory::fromFile($config);
            } else {
                throw new Exception\InvalidArgumentException(sprintf(
                    'Config was a string but file "%s" does not exist',
                    $config
                ));
            }
        } elseif ($config instanceof Config\Config) {
            $config = $config->toArray();
        } elseif (!is_array($config)) {
            throw new Exception\InvalidArgumentException('
                Invalid input, expected array, filename, or Zend\Config object'
            );
        }

        return $config;
    }

    /**
     * @param array $pages
     * @param RouteMatch $routeMatch
     * @param Router $router
     * @param null|Request $request
     * @return mixed
     */
    protected function injectComponents(array $pages, RouteMatch $routeMatch = null, Router $router = null, $request = null)
    {
        foreach ($pages as &$page) {
            $hasUri = isset($page['uri']);
            $hasMvc = isset($page['action']) || isset($page['controller']) || isset($page['route']);
            if ($hasMvc) {
                if (!isset($page['routeMatch']) && $routeMatch) {
                    $page['routeMatch'] = $routeMatch;
                }
                if (!isset($page['router'])) {
                    $page['router'] = $router;
                }
            } elseif ($hasUri) {
                if (!isset($page['request'])) {
                    $page['request'] = $request;
                }
            }

            if (isset($page['pages'])) {
                $page['pages'] = $this->injectComponents($page['pages'], $routeMatch, $router, $request);
            }
        }
        return $pages;
    }

}
