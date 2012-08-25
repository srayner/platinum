<?php

namespace Inventory\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * A stock transaction type.
 *
 * @ORM\Entity
 * @ORM\Table(name="sc_trans_types")
 */
class TransType extends Entity
{
	protected $inputFilter;

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
}