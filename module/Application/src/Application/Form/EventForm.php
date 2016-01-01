<?php

namespace Application\Form;

use Zend\Form\Form;

class EventForm extends Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setName('event');
        $this->setAttribute('method', 'post');
        $this->add(array(
                'name' => 'id',
                'attributes' => array(
                        'type' => 'hidden',
                ),
        ));

        // Title.
        $this->add(array(
                'name' => 'title',
                'options' => array(
                        'label' => 'Title'
                ),
                'attributes' => array(
                        'type' => 'text'
                ),
        ));

        // All Day.
        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'allday',
            'options' => array(
                'label' => 'All Day',
                'use_hidden_element' => true,
                'checked_value' => 'true',
                'unchecked_value' => 'false'
            )
        ));
        
        // Start.
        $this->add(array(
                'name' => 'start',
                'options' => array(
                        'label' => 'Start'
                ),
                'attributes' => array(
                        'type' => 'text',
                        'class' => 'datepicker'
                ),
        ));
        
        // End.
        $this->add(array(
                'name' => 'end',
                'options' => array(
                        'label' => 'End'
                ),
                'attributes' => array(
                        'type' => 'text',
                        'class' => 'datepicker'
                ),
        ));
        
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