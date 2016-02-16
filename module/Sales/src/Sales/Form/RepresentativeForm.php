<?php

namespace Sales\Form;
use Zend\Form\Form;

class RepresentativeForm extends Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setName('representative');
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
        
        // firstname
	$this->add(array(
	    'name' => 'firstname',
	    'options' => array(
	        'label' => 'First Name'
	    ),
	    'attributes' => array(
	        'type' => 'text'
	    ),
        ));
        
        // lastname
	$this->add(array(
	    'name' => 'lastname',
	    'options' => array(
	        'label' => 'Last Name'
	    ),
	    'attributes' => array(
	        'type' => 'text'
	    ),
        ));
        
        // description
	$this->add(array(
	    'name' => 'description',
	    'options' => array(
	        'label' => 'Description'
            )
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
