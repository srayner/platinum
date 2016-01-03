<?php

namespace Sales;

return array(
    
    // Router configuration.
    'router' => array(
        'routes' => array(
            'sales' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/sales',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Sales\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action][/:id]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                    
                            ),
                        ),
                    ),
                ),
            ),
	),
    ),
    
    // Controller configurtion.
    'controllers' => array(
        'invokables' => array(
            'Sales\Controller\Index'          => 'Sales\Controller\IndexController',
        ),
    ),

    // View manager configuration.
    'view_manager' => array(
        'template_map' => include __DIR__  .'/../template_map.php',
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    
);
