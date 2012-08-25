<?php
namespace Inventory;

return array(
    'router' => array(
		'routes' => array(
			
			'items' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/items[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
					),
					'defaults' => array(
						'controller' => 'inventory/items',
						'action' => 'index',
					),
				),
			),
				
			'categories' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/categories[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
					),
					'defaults' => array(
						'controller' => 'inventory/categories',
						'action' => 'index',
					),
				),
			),
				
			'locations' => array(
				'type' => 'segment',
					'options' => array(
					'route' => '/locations[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
					),
					'defaults' => array(
						'controller' => 'inventory/locations',
						'action' => 'index',
					),
				),
			),
				
			'products' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/products[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
					),
					'defaults' => array(
						'controller' => 'inventory/products',
						'action' => 'index',
					),
				),
			),
				
		),
	),
	'controllers' => array(
		'invokables' => array(
			'inventory/items' => 'Inventory\Controller\ItemsController',
			'inventory/categories' => 'Inventory\Controller\CategoriesController',
			'inventory/locations' => 'Inventory\Controller\LocationsController',
			'inventory/products' => 'Inventory\Controller\ProductsController'
		),
	),
	'view_manager' => array(
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
