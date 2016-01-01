<?php
namespace Inventory\Entity;

use Doctrine\ORM\Mapping as ORM,
    Zend\InputFilter\InputFilter,
    Zend\InputFilter\Factory as InputFactory;

/**
 * A stock transaction
 * @author Steve
 * 
 * @ORM\Entity
 * @ORM\Table(name="sc_transactions")
 */
class Transaction extends Entity
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer");
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @var integer
	 */
	protected $id;
	
	/**
	 * @ORM\Column(type="datetime")
	 * @var unknown_type
	 */
	protected $input_date;
	
	/**
	 * @ORM\Column(type="datetime")
	 * @var unknown_type
	 */
	protected $trans_date;
	
	/**
	 * @ORM\ManyToOne(targetEntity="MovementType")
     * @ORM\JoinColumn(name="movement_type_id", referencedColumnName="id", nullable=false)
	 * @var integer
	 */
	protected $movement_type;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Location")
	 * @ORM\JoinColumn(name="location_id", referencedColumnName="id", nullable=false)
	 * @var integer
	 */
	protected $location;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Item")
	 * @ORM\JoinColumn(name="item_id", referencedColumnName="id", nullable=false)
	 * @var integer
	 */
	protected $item;
	
	/**
	 * @ORM\Column(type="integer")
	 * @var integer
	 */
	protected $qty;
	
	/**
	 * @ORM\Column(type="string", length=64, nullable=true)
	 * @var unknown_type
	 */
	protected $narrative;
	
	/**
	 * Constructor
	 * @param unknown_type $type
	 * @param unknown_type $item
	 * @param unknown_type $location
	 * @param unknown_type $qty
	 */
	public function __construct($type, $item, $location, $qty)
	{
	    $this->movement_type = $type;
	    $this->item = $item;
	    $this->location = $location;
	    $this->qty = $qty;
	    $this->input_date = new \DateTime();
	    $this->trans_date = new \DateTime();
	}
	
    /**
     * Populate from an array.
     *
     * @param array $data
     */
    public function populate($data = array())
    {
        $this->id = $data['id'];
        $this->qty = $data['qty'];
        
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
	        
	        // Qty input filter.
	        $inputFilter->add($factory->createInput(array(
	                'name' => 'qty',
	                'required' => true,
	                'filters' => array(
	                        array('name' => 'Int'),
	                ),
	        )));
	    
	        // Narrative filter.
	        $inputFilter->add($factory->createInput(array(
	            'name' => 'narative',
	            'required' => false,
	                'filters' => array(
	                    array('name' => 'StripTags'),
	                    array('name' => 'StringTrim'),
	                ),
	                'validators' => array(
	                    array(
	                        'name' => 'StringLength',
	                        'options' => array(
	                            'encoding' => 'UTF-8',
	                            'min' => 0,
	                            'max' => 64,
	                        ),
	                    ),
	                ),
	        )));
	        
	        $this->inputFilter = $inputFilter;
        }
    
        return $this->inputFilter;
    }
}