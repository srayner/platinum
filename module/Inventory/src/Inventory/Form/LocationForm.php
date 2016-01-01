<?php

namespace Inventory\Form;

use Zend\Form\Form;

class LocationForm extends Form
{
	public function __construct()
	{
		parent::__construct();
		$this->setName('location');
		$this->setAttribute('method', 'post');
		$this->add(array(
				'name' => 'id',
				'attributes' => array(
						'type' => 'hidden',
				),
		));

		// location code.
		$this->add(array(
				'name' => 'location_code',
				'options' => array(
						'label' => 'Location Code'
				),
				'attributes' => array(
						'type' => 'text'
				),
		));
		
		// location name.
		$this->add(array(
				'name' => 'location_name',
				'options' => array(
						'label' => 'Location Name'
				),
				'attributes' => array(
						'type' => 'text'
				),
		));

		// contact name.
		$this->add(array(
				'name' => 'contact_name',
				'options' => array(
						'label' => 'Contact for deliveries'
				),
				'attributes' => array(
						'type' => 'text'
				),
		));
		
		// phone number.
		$this->add(array(
				'name' => 'phone',
				'options' => array(
						'label' => 'Telephone Number'
				),
				'attributes' => array(
						'type' => 'text'
				),
		));
		
		// fax number.
		$this->add(array(
				'name' => 'fax',
				'options' => array(
						'label' => 'Fax Number'
				),
				'attributes' => array(
						'type' => 'text'
				),
		));
		
		// email address.
		$this->add(array(
				'name' => 'email',
				'options' => array(
						'label' => 'Email Address'
				),
				'attributes' => array(
						'type' => 'text'
				),
		));
		
		$this->add(array(
				'name' => 'submit',
				'attributes' => array(
						'type' => 'Submit',
						'value' => 'Go',
						'id' => 'submitbutton',
				        'class' => 'btn btn-primary'
				),
		));

	}
}