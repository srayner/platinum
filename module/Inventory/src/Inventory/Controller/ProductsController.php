<?php
namespace Inventory\Controller;

use Zend\Mvc\Controller\AbstractActionController,
Zend\View\Model\ViewModel,
Inventory\Form\CreateProduct,
Inventory\Entity\Product;

class ProductsController extends AbstractActionController
{
	/**
	 * @return array
	 */
	public function indexAction()
	{
		$form = new CreateProduct();
		$product = new Product();
		$form->bind($product);
	
		if ($this->request->isPost()) {
			$form->setData($this->request->getPost());
	
			if ($form->isValid()) {
				var_dump($product);
			}
		}
	
		return array(
				'form' => $form
		);
	}
	
}