<?php

namespace Inventory\Form;

use Zend\Form\Form,
    Doctrine\Common\Collections,
    Doctrine\ORM\EntityManager,
    Doctrine\ORM\Query;

class TransferForm extends Form
{
    public function __construct($em)
    {
        parent::__construct();
        $this->setName('transfer');
        $this->setAttribute('method', 'post');
        
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
        
        // Build an array to hold the locations.
        $query = $em->createQuery('SELECT l.id, l.location_name FROM Inventory\Entity\Location l');
        $result = $query->getResult(Query::HYDRATE_ARRAY);
        foreach($result as $row) $locations[$row['id']] = $row['location_name'];
        
        // From location id.
        $this->add(array(
            'name' => 'from_location_id',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'From Location'
            ),
            'attributes' => array(
                'options' => $locations,
            )
        ));
        
        // To location id.
        $this->add(array(
            'name' => 'to_location_id',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'To Location'
            ),
            'attributes' => array(
                'options' => $locations,
            )
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