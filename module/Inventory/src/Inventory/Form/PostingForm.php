<?php

namespace Inventory\Form;

use Zend\Form\Form;

class PostingForm extends Form
{
	public function __construct($em)
	{
		parent::__construct();
		$this->setName('posting');
		$this->setAttribute('method', 'post');
	
		// Transaction type
		
		// Item Code
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
		
		// Location Id.
		$query = $em->createQuery('SELECT l.id, l.location_name FROM Inventory\Entity\Location l');
		$result = $query->getResult(Query::HYDRATE_ARRAY);
		foreach($result as $row) $locations[$row['id']] = $row['location_name'];
		$this->add(array(
				'name' => 'location_id',
				'type' => 'Zend\Form\Element\Select',
				'options' => array(
						'label' => 'Location'
				),
				'attributes' => array(
						'options' => $locations,
				)
		));
		
		// Qty
		
		
	}
}