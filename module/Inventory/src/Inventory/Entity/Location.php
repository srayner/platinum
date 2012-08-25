<?php

namespace Inventory\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * An inventory location.
 *
 * @ORM\Entity
 * @ORM\Table(name="sc_locations")
 * @property int $id
 * @property string $location_code
 * @property string $location_name
 * @property string $contact_name
 * @property string $phone
 * @property string $fax
 * @property string $email
 */
class Location implements InputFilterAwareInterface
{
	protected $inputFilter;

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer");
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=8)
	 */
	protected $location_code;

	/**
	 * @ORM\Column(type="string", length=128)
	 */
	protected $location_name;
	
	/**
	 * @ORM\Column(type="string", length=64, nullable=true)
	 */
	protected $contact_name;
	
	/**
	 * @ORM\Column(type="string", length=24, nullable=true)
	 */
	protected $phone;
	
	/**
	 * @ORM\Column(type="string", length=24, nullable=true)
	 */
	protected $fax;
	
	/**
	 * @ORM\Column(type="string", length=64, nullable=true)
	 */
	protected $email;
	
	/**
	 * Magic getter to expose protected properties.
	 *
	 * @param string $property
	 * @return mixed
	 */
	public function __get($property)
	{
		return $this->$property;
	}
	
	/**
	 * Magic setter to save protected properties.
	 *
	 * @param string $property
	 * @param mixed $value
	 */
	public function __set($property, $value)
	{
		$this->$property = $value;
	}
	
	/**
	 * Convert the object to an array.
	 *
	 * @return array
	 */
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
	
	/**
	 * Populate from an array.
	 *
	 * @param array $data
	 */
	public function populate($data = array())
	{
		$this->id = $data['id'];
		$this->location_code = $data['location_code'];
		$this->location_name = $data['location_name'];
		$this->contact_name = $data['contact_name'];
		$this->phone_no = $data['phone_no'];
		$this->fax_no = $data['fax_no'];
		$this->email = $data['email'];
	}
	
	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \Exception("Not used");
	}
	
	public function getInputFilter()
	{
		if (!$this->inputFilter) {
			$inputFilter = new InputFilter();
	
			$factory = new InputFactory();
	
			$inputFilter->add($factory->createInput(array(
					'name' => 'id',
					'required' => true,
					'filters' => array(
							array('name' => 'Int'),
					),
			)));
	
			$inputFilter->add($factory->createInput(array(
					'name' => 'location_code',
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
	
			$inputFilter->add($factory->createInput(array(
				'name' => 'location_name',
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
			
			$inputFilter->add($factory->createInput(array(
				'name' => 'contact_name',
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