<?php

namespace Application\Form;

use Zend\Form\Form;

class UserForm extends Form
{

	public function __construct()
	{
		parent::__construct();
		$this->setName('user');
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form label-inline');
		$this->add(array(
				'name' => 'id',
				'attributes' => array(
						'type' => 'hidden',
				),
		));

		// Username.
		$this->add(array(
				'name' => 'username',
				'options' => array(
						'label' => 'Username'
				),
				'attributes' => array(
						'type' => 'text',
						'size'=> '50'
				),
		));

		// Password.
		$this->add(array(
				'name' => 'password',
				'options' => array(
						'label' => 'Password'
				),
				'attributes' => array(
						'type' => 'password',
						'size'=> '50'
				),
		));
		
		// Email address.
		$this->add(array(
				'name' => 'email',
				'options' => array(
						'label' => 'Email Address'
				),
				'attributes' => array(
						'type' => 'text',
						'size'=> '50'
				),
		));
		
		// Display Name.
		$this->add(array(
				'name' => 'displayName',
				'options' => array(
						'label' => 'Display Name'
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