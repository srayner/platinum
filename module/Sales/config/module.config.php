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
            'Sales\Controller\Area'           => 'Sales\Controller\AreaController',
            'Sales\Controller\Branch'         => 'Sales\Controller\BranchController',
            'Sales\Controller\Order'          => 'Sales\Controller\OrderController',
            'Sales\Controller\Representative' => 'Sales\Controller\RepresentativeController',
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
            'sales_account'        => 'Sales\Entity\Account',
            'sales_area'           => 'Sales\Entity\Area',
            'sales_branch'         => 'Sales\Entity\Branch',
            'sales_order'          => 'Sales\Entity\Order',
            'sales_order_line'     => 'Sales\Entity\OrderLine',
            'sales_representative' => 'Sales\Entity\Representative',
        ),
        'factories' => array(
            'sales_account_form'        => 'Sales\Form\AccountFormFactory',
            'sales_area_form'           => 'Sales\Form\AreaFormFactory',
            'sales_branch_form'         => 'Sales\Form\BranchFormFactory',
            'sales_order_form'          => 'Sales\Form\OrderFormFactory',
            'sales_line_form'           => 'Sales\Form\LineFormFactory',
            'sales_representative_form' => 'Sales\Form\RepresentativeFormFactory',
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
