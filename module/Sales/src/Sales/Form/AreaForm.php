<?php

namespace Sales\Form;

use Zend\Form\Form;

class AreaForm extends Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setName('area');
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
        
        // code
	$this->add(array(
	    'name' => 'code',
	    'options' => array(
	        'label' => 'Code'
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