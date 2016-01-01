<?php

namespace Inventory\Form;

use Zend\Form\Form,
    Doctrine\Common\Collections,
    Doctrine\ORM\EntityManager,
    Doctrine\ORM\Query;

class PostingForm extends Form
{
	public function __construct($em)
	{
		parent::__construct();
		$this->setName('posting');
		$this->setAttribute('method', 'post');
		
		// Item Code.
		$this->add(array(
		    'name' => 'item_code',
			'options' => array(
				'label' => 'Item Code'
			),
			'attributes' => array(
				'type' => 'text',
				'size'=> '50',
			    'class' => 'typeahead',
			    'data-provide' => 'typeahead',
			    'autocomplete' => 'off'
			),
		));
		
		// Qty.
		$this->add(array(
		    'name' => 'qty',
		    'options' => array(
		        'label' => 'Qty',
		    ),
		    'attribute' => array(
		        'type' => 'text',
		        'size', '10',
		        'autocomplete' => 'off'
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
		
		// Submit button.
		$this->add(array(
		        'name' => 'submit',
		        'attributes' => array(
		                'type' => 'submit',
		                'value' => 'Go',
		                'id' => 'submitbutton',
		                'class' => 'btn btn-primary'
		        ),
		));
		
		
		
	}
}