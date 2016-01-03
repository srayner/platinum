<?php

namespace Sales\Form;
use Zend\Form\Form;

class AccountForm extends Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setName('account');
	$this->setAttribute('method', 'post');
        $this->addElements();
    }
    
    private function addElements()
    {
        $this->add(array(
            'name' => 'id',
	    'attributes' => array(
		'type' => 'hidden',
	    ),
	));
        
        // account_number
	$this->add(array(
	    'name' => 'account_number',
	    'options' => array(
	        'label' => 'Name'
	    ),
	    'attributes' => array(
	        'type' => 'text'
	    ),
        ));
        
        // company_name
	$this->add(array(
	    'name' => 'company_name',
	    'options' => array(
	        'label' => 'Company Name'
	    ),
	    'attributes' => array(
	        'type' => 'text'
	    ),
        ));
        
        // address
	$this->add(array(
            'type' => 'Textarea',
	    'name' => 'address',
	    'options' => array(
	        'label' => 'Address'
            )
        ));
        
        // post_code
	$this->add(array(
	    'name' => 'post_code',
	    'options' => array(
	        'label' => 'Postcode'
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
