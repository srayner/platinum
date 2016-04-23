<?php

namespace Purchasing;

return array(
    
    // Router configuration.
    'router' => array(
        'routes' => array(
            'purchasing' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/purchasing',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Purchasing\Controller',
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
            'Purchasing\Controller\Index'     => 'Purchasing\Controller\IndexController',
            'Purchasing\Controller\Account'   => 'Purchasing\Controller\AccountController',
            'Purchasing\Controller\Order'     => 'Purchasing\Controller\OrderController',
        ),
    ),

    // View manager configuration.
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    
    // Service manager
    'service_manager' => array(
        'invokables' => array(
            'purchasing_account'    => 'Purchasing\Entity\Account',
            'purchasing_order'      => 'Purchasing\Entity\Order',
            'purchasing_order_line' => 'Purchasing\Entity\OrderLine',
        ),
        'factories' => array(
            'purchasing_account_form'    => 'Purchasing\Form\AccountFormFactory',
            'purchasing_order_form'      => 'Purchasing\Form\OrderFormFactory',
            'purchasing_order_line_form' => 'Purchasing\Form\LineFormFactory',
        ),
    ),
    
    // Doctrine configuration.
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
                ),
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ),
            ),
        ),
    ),
    
);