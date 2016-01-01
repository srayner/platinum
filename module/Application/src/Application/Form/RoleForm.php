<?php
namespace Application\Form;

use Zend\Form\Form;

class RoleForm extends Form
{

	public function __construct()
	{
		parent::__construct();
		$this->setName('role');
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form label-inline');
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
						'label' => 'Role Name'
				),
				'attributes' => array(
						'type' => 'text',
						'size'=> '50'
				),
		));
		
		// Default.
		$this->add(array(
				'name' => 'default',
				'options' => array(
						'label' => 'Default'
				),
				'attributes' => array(
						'type' => 'text',
						'size'=> '50'
				),
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