<?php
namespace Inventory;

return array(
    'router' => array(
		'routes' => array(
		    'inventory' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/inventory',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Inventory\Controller',
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
	'controllers' => array(
		'invokables' => array(
		    'Inventory\Controller\Index'          => 'Inventory\Controller\IndexController',    
			'Inventory\Controller\Items'          => 'Inventory\Controller\ItemsController',
			'Inventory\Controller\Categories'     => 'Inventory\Controller\CategoriesController',
			'Inventory\Controller\Locations'      => 'Inventory\Controller\LocationsController',
			'Inventory\Controller\movement-types' => 'Inventory\Controller\MovementTypesController',
		    'Inventory\Controller\Units'          => 'Inventory\Controller\UnitsController',
		    'Inventory\Controller\Posting'        => 'Inventory\Controller\PostingController',
		),
	),
	'view_manager' => array(
	    'template_map' => include __DIR__  .'/../template_map.php',
		'template_path_stack' => array(
			__DIR__ . '/../view',
		),
	),
	
	// Doctrine config
	'doctrine' => array(
		'driver' => array(
			__NAMESPACE__ . '_driver' => array(
				'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'cache' => 'array',
				'paths' => array(
					__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
				)
			),
			'orm_default' => array(
				'drivers' => array(
					__NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
				)
			)
		)
	),
);
