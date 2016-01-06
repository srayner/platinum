<?php

namespace Sales\Form;

use Zend\InputFilter\InputFilter;

class OrderFilter extends InputFilter
{
    public function __construct()
    {
        // Customer Ref
        $this->add(array(
            'name'       => 'customerRef',
            'required'   => false,
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
    }
}
