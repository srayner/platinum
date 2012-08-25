<?php

namespace Inventory\Form;

use Zend\Form\Form;

class CategoryForm extends Form
{
	public function __construct()
	{
		parent::__construct();
		$this->setName('category');
		$this->setAttribute('method', 'post');
		$this->add(array(
				'name' => 'id',
				'attributes' => array(
						'type' => 'hidden',
				),
		));

		// name.
		$this->add(array(
				'name' => 'name',
				'options' => array(
						'label' => 'Name'
				),
				'attributes' => array(
						'type' => 'text'
				),
		));

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