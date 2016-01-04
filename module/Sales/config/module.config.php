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
            'Sales\Controller\Account'        => 'Sales\Controller\AccountController',
            'Sales\Controller\Branch'         => 'Sales\Controller\BranchController',
        ),
    ),

    // View manager configuration.
    'view_manager' => array(
        'template_map' => include __DIR__  .'/../template_map.php',
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    
    // Service manager
    'service_manager' => array(
        'invokables' => array(
            'sales_account' => 'Sales\Entity\Account',
            'sales_branch'  => 'Sales\Entity\Branch',
        ),
        'factories' => array(
            'sales_account_form' => 'Sales\Form\AccountFormFactory',
            'sales_branch_form'  => 'Sales\Form\BranchFormFactory',
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
