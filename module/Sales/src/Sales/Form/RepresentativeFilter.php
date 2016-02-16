<?php

namespace Sales\Form;

use Zend\InputFilter\InputFilter;

class RepresentativeFilter extends InputFilter
{
    public function __construct()
    {
        // Firstname
        $this->add(array(
            'name'       => 'firstname',
            'required'   => true,
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'max' => 24,
                    ),
                ),
            ),
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));
        
        // Lastname
        $this->add(array(
            'name'       => 'lastname',
            'required'   => true,
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'max' => 24,
                    ),
                ),
            ),
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));
        
        // description
        $this->add(array(
            'name'       => 'description',
            'required'   => true,
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'max' => 128,
                    ),
                ),
            ),
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));
    }
}