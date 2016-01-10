<?php

namespace Sales\Form;

use Zend\InputFilter\InputFilter;

class LineFilter extends InputFilter
{
    public function __construct()
    {
        // Item Code
        $this->add(array(
            'name'       => 'item_code',
            'required'   => true,
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'max' => 32,
                    ),
                ),
            ),
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));
        
        // Qty
        $this->add(array(
            'name'       => 'qty',
            'required'   => true,
            'validators' => array(
                array(
                    'name'    => 'Digits',
                ),
            ),
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));
    }
}