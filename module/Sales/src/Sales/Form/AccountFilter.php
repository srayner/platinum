<?php

namespace Sales\Form;

use Zend\InputFilter\InputFilter;

class AccountFilter extends InputFilter
{
    public function __construct()
    {
        // Account number
        $this->add(array(
            'name'       => 'accountNumber',
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
        
        // Company Name
        $this->add(array(
            'name'       => 'companyName',
            'required'   => true,
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'max' => 64,
                    ),
                ),
            ),
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));
        
        // Address
        $this->add(array(
            'name'       => 'address',
            'required'   => false,
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));
        
        // Postcode
        $this->add(array(
            'name'       => 'postcode',
            'required'   => false,
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'max' => 8,
                    ),
                ),
            ),
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));
    }
}