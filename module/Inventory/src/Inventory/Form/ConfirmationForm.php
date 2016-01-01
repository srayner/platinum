<?php

namespace Inventory\Form;

use Zend\Form\Form;

class ConfirmationForm extends Form
{

    public function __construct()
    {
        parent::__construct();
        
        // Hidden id.
        $this->add(array(
                'name' => 'id',
                'attributes' => array(
                        'type' => 'hidden',
                ),
        ));
        
        // Yes submit button.
        $this->add(array(
                'name' => 'yes',
                'attributes' => array(
                        'type' => 'submit',
                        'value' => 'Yes',
                        'id' => 'submitbutton',
                ),
        ));
        
        // No submit button.
        $this->add(array(
                'name' => 'no',
                'attributes' => array(
                        'type' => 'submit',
                        'value' => 'No',
                        'id' => 'submitbutton',
                ),
        ));
        
    }

}