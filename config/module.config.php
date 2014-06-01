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
    ),
    'service_manager' => array(
        'aliases' => array(
            'zend_db_adapter' => 'Zend\Db\Adapter\Adapter',
        ),
    ),
    'router' => array(
        'routes' => array(
		),
    ),
);
