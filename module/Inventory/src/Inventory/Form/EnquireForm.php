<?php
namespace Inventory\Form;

use Zend\Form\Form;

class EnquireForm extends Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setName('enquire');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form label-inline');
    
        // Item Code.
        $this->add(array(
                'name' => 'item_code',
                'options' => array(
                        'label' => 'Item Code'
                ),
                'attributes' => array(
                        'id' => 'item_code',
                        'type' => 'text',
                        'size'=> '50',
                        'autocomplete' => 'off'
                ),
        ));
        
        // Submit button.
        $this->add(array(
                'name' => 'submit',
                'attributes' => array(
                        'type' => 'submit',
                        'value' => 'Enquire',
                        'id' => 'submitbutton',
                        'class' => 'btn btn-primary'
                ),
        ));
    }
    
}
