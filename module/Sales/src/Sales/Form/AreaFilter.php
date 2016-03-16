<?php

namespace Sales\Form;

use Zend\InputFilter\InputFilter;

class AreaFilter extends InputFilter
{
    public function __construct()
    {
        // Code
        $this->add(array(
            'name'       => 'code',
            'required'   => true,
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'max' => 16,
                    ),
                ),
            ),
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));
        
        // Description
        $this->add(array(
            'name'       => 'code',
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
