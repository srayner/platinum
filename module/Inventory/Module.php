<?php
namespace Inventory;

class Module
{
	public function getAutoloaderConfig()
	{
		return array(
				'Zend\Loader\ClassMapAutoloader' => array(
						__DIR__ . '/autoload_classmap.php',
				),
				'Zend\Loader\StandardAutoloader' => array(
						'namespaces' => array(
								__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
						),
				),
		);
	}
	
	public function getConfig()
	{
		return include __DIR__ . '/config/module.config.php';
	}
	
	public function getViewHelperConfig()
	{
		return array(
			'invokables' => array(
				'RenderForm' => 'Inventory\View\Helper\RenderForm'
			)
		);
		
	}
	
}