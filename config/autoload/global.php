<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overridding configuration values from modules, etc.  
 * You would place values in here that are agnostic to the environment and not 
 * sensitive to security. 
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source 
 * control, so do not include passwords or other sensitive information in this 
 * file.
 */

return array(
    // All navigation-related configuration is collected in the 'navigation' key
    'navigation' => array(
        // The DefaultNavigationFactory we configured in (1) uses 'default' as the sitemap key
        'default' => array(
            // And finally, here is where we define our page hierarchy
            array(
                'label' => 'Home',
                'route' => 'default',
                'controller' => 'index'
            ),
            array(
        		'label' => 'Inventory',
        		'route' => 'inventory',
                'controller' => 'index'
        	),
        	array(
        		'label' => 'Sales',
        		'route' => 'roles',
        		'controller' => 'roles'
        	),	
        	array(
        		'label' => 'Purchasing',
        		'route' => 'users',
        		'controller' => 'users'
        	),
        ),
    ),
);
