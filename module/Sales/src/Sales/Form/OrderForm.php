<?php

namespace Sales\Form;
use Zend\Form\Form;

class OrderForm extends Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setName('order');
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
	    'name' => 'accountNumber',
	    'options' => array(
	        'label' => 'Account Number'
	    ),
	    'attributes' => array(
	        'type' => 'text'
	    ),
        ));
        
        // customer_ref
	$this->add(array(
	    'name' => 'customerRef',
	    'options' => array(
	        'label' => 'Customer Ref'
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
