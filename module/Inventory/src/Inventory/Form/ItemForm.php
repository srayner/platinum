<?php

namespace Inventory\Form;

use Zend\Form\Form,
    Doctrine\Common\Collections,
    Doctrine\ORM\EntityManager,
    Doctrine\ORM\Query,    
    Inventory\Entity\Category;

class ItemForm extends Form
{
	
	public function __construct($em)
	{
		parent::__construct();
		$this->setName('item');
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form label-inline');
		$this->add(array(
				'name' => 'id',
				'attributes' => array(
						'type' => 'hidden',
				),
		));

		// Item Code.
		$this->add(array(
				'name' => 'item_code',
				'options' => array(
					'label' => 'Item Code'
				),
				'attributes' => array(
						'type' => 'text',
						'size'=> '50'
				),
		));

		// Short Description.
		$this->add(array(
				'name' => 'short_description',
				'options' => array(
						'label' => 'Short Description'
				),
				'attributes' => array(
						'type' => 'text',
						'size'=> '50'
				),
		));
		
		// Category Id.
		$query = $em->createQuery('SELECT c.id, c.name FROM Inventory\Entity\Category c');
		$result = $query->getResult(Query::HYDRATE_ARRAY);
		foreach($result as $row) $categories[$row['id']] = $row['name'];
		$this->add(array(
			'name' => 'category_id',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => 'Category'
			),
			'attributes' => array(
				'options' => $categories,
			)
		));
		
		// Submit button.
		$this->add(array(
				'name' => 'submit',
				'attributes' => array(
						'type' => 'submit',
						'value' => 'Go',
						'id' => 'submitbutton',
				),
		));

	}
}