<?php
namespace Inventory\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * A Stock Stransaction
 * @author Steve
 * 
 * @ORM\Entity
 * @ORM\Table(name="sc_transactions")
 */
class Transaction extends Entity
{
	protected $inputFilter;

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
	 * @ORM\ManyToOne(targetEntity="TransType")
     * @ORM\JoinColumn(name="trans_type_id", referencedColumnName="id")
	 * @var integer
	 */
	protected $trans_type_id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Location")
	 * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
	 * @var integer
	 */
	protected $location_id;
	
	/**
	 * @ORM\Column(type="integer")
	 * @var integer
	 */
	protected $qty;
	
	/**
	 * @ORM\Column(type="string", length=128)
	 * @var unknown_type
	 */
	protected $narrative;
	
}