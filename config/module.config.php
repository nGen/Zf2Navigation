<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            'view_location' => __DIR__ . '/../view',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
        ),
        'factories' => array(           
            'nGenZf2NavigationContainerController' => 'nGen\Zf2Navigation\NavigationManagement\Controller\Factory\NavigationContainerControllerFactory',
            'nGenZf2NavigationPageController' => 'nGen\Zf2Navigation\NavigationManagement\Controller\Factory\NavigationPageControllerFactory',
        ),
    ),
    'service_manager' => array(
        'aliases' => array(
            'zend_db_adapter' => 'Zend\Db\Adapter\Adapter',
        ),
        'factories' => array(            
            'nGenZf2NavigationContainerService' => 'nGen\Zf2Navigation\NavigationManagement\Service\Factory\NavigationContainerServiceFactory',
            'nGenZf2NavigationContainerMapper' => 'nGen\Zf2Navigation\NavigationManagement\Mapper\Factory\NavigationContainerMapperFactory',            
            'nGenZf2NavigationPageService' => 'nGen\Zf2Navigation\NavigationManagement\Service\Factory\NavigationPageServiceFactory',
            'nGenZf2NavigationPageMapper' => 'nGen\Zf2Navigation\NavigationManagement\Mapper\Factory\NavigationPageMapperFactory',
        )
    ),
    'view_helpers' => array(
        'invokables' => array(
            'nGenNavigation' => 'nGen\Zf2Navigation\NavigationManagement\Helper\NgenNavigation',
        ),
    ),
);
