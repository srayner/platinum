<?php
namespace Inventory\Form;

use Zend\Form\Form;

class MovementTypeForm extends Form
{

    public function __construct()
    {
        parent::__construct();
        $this->setName('movement-type');
        $this->setAttribute('method', 'post');
        
        // Hidden id field.
        $this->add(array(
                'name' => 'id',
                'attributes' => array(
                        'type' => 'hidden',
                ),
        ));
        
        // movement type code.
        $this->add(array(
                'name' => 'code',
                'options' => array(
                        'label' => 'Movement Type Code'
                ),
                'attributes' => array(
                        'type' => 'text'
                ),
        ));
        
        // movement type name.
        $this->add(array(
                'name' => 'name',
                'options' => array(
                        'label' => 'Movement Type Name'
                ),
                'attributes' => array(
                        'type' => 'text'
                ),
        ));
        
        // Submit button
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