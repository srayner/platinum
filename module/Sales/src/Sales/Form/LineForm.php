<?php

namespace Sales\Form;
use Zend\Form\Form;

class LineForm extends Form
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