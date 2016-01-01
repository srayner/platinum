<?php

namespace Inventory\Entity;

use Doctrine\ORM\Mapping as ORM,
    Zend\InputFilter\InputFilter,
    Zend\InputFilter\Factory as InputFactory;

/**
 * A stock transaction type.
 *
 * @ORM\Entity
 * @ORM\Table(name="sc_trans_types")
 */
class MovementType extends Entity
{
    // Built in transaction types.
    const GOODS_RECEIPT  = 1;
    const STOCK_TRANSFER = 2;
    const STOCK_ADJUSTMENT = 3;
    const GOODS_BOOKOUT = 4;
    
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @var integer
	 */ 
	protected $id;

	/**
	 * @ORM\Column(type="string", length=3)
	 * @var unknown_type
	 */
	protected $code;
	
	/**
	 * @ORM\Column(type="string", length=64)
	 * @var unknown_type
	 */
	protected $name;
	
	/**
	 * Populate from an array.
	 *
	 * @param array $data
	 */
	public function populate($data = array())
	{
	    $this->id = $data['id'];
	    $this->code = $data['code'];
	    $this->name = $data['name'];
	}
	
	public function getInputFilter()
	{
	    if (!$this->inputFilter)
	    {
	        $inputFilter = new InputFilter();
	        $factory = new InputFactory();
	
	        // Id input filter.
	        $inputFilter->add($factory->createInput(array(
	            'name' => 'id',
	            'required' => true,
	            'filters' => array(
	                array('name' => 'Int'),
	            ),
	        )));
	
	        // Code input filter.
	        $inputFilter->add($factory->createInput(array(
	            'name' => 'code',
	            'required' => true,
	            'filters' => array(
	                array('name' => 'StripTags'),
	                array('name' => 'StringTrim'),
	            ),
	            'validators' => array(
	                array(
	                    'name' => 'StringLength',
	                    'options' => array(
	                        'encoding' => 'UTF-8',
	                         'min' => 1,
	                         'max' => 8,
	                    ),
	                ),
	            ),
	        )));

	        // Name input filter.
	        $inputFilter->add($factory->createInput(array(
	            'name' => 'name',
	            'required' => true,
	            'filters' => array(
	                array('name' => 'StripTags'),
	                array('name' => 'StringTrim'),
	            ),
	            'validators' => array(
	                array(
	                    'name' => 'StringLength',
	                    'options' => array(
	                        'encoding' => 'UTF-8',
	                        'min' => 1,
	                        'max' => 128,
	                    ),
	                ),
	            ),
	        )));
	        	
	        $this->inputFilter = $inputFilter;
	    }
	
	    return $this->inputFilter;
	}
}