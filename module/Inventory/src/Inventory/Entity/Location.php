<?php

namespace Inventory\Entity;

use Doctrine\ORM\Mapping as ORM,
    Zend\InputFilter\InputFilter,
    Zend\InputFilter\Factory as InputFactory;

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
class Location extends Entity
{
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
		$this->phone = $data['phone'];
		$this->fax = $data['fax'];
		$this->email = $data['email'];
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
	
			// Location code input filter.
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
	
			// Location name input filter.
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
			
			// Contact name input filter.
			$inputFilter->add($factory->createInput(array(
				'name' => 'contact_name',
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
			
			// Phone input filter.
			$inputFilter->add($factory->createInput(array(
			    'name' => 'phone',
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
			
			// fax input filter
			$inputFilter->add($factory->createInput(array(
			    'name' => 'fax',
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
			
			// email input filter.
			$inputFilter->add($factory->createInput(array(
			    'name' => 'email',
			    'required' => false,
			    'filters' => array(
			        array('name' => 'StripTags'),
			            array('name' => 'StringTrim'),
			        ),
			        'validators' => array(
			            array(
			                'name' => 'EmailAddress',
			                'options' => array(
			            ),
			        ),
			    ),
			)));
	
			$this->inputFilter = $inputFilter;
		}
	
		return $this->inputFilter;
	}
}