<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

/**
 * An application user role.
 *
 * @ORM\Entity
 * @ORM\Table(name="user_role")
 */
class Role extends Entity
{
	protected $inputFilter;
	
	/**
	 * @var int
	 * @ORM\Id
	 * @ORM\Column(name="role_id", type="integer");
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $name;
	
	/**
	 * @var int
	 * @ORM\Column(name="default", type="smallint");
	 */
	protected $default;
	
	/**
	 * @var int
	 * @ORM\Column(name="parent", type="integer", nullable=true);
	 */
	protected $parent;
	
	/**
	 * setInputFilter
	 * @param InputFilterInterface $inputFilter
	 * @throws \Exception
	 */
	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \Exception("Not used");
	}
	
	/**
	 * 
	 */
	public function getInputFilter()
	{
		if (!$this->inputFilter)
		{
			$inputFilter = new InputFilter();
			$factory     = new InputFactory();
	
			// Id
			$inputFilter->add($factory->createInput(array(
				'name' => 'id',
				'required' => true,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));
			
			// Name.
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
							'max' => 255,
						),
					),
				),
			)));
			
			// Default
			$inputFilter->add($factory->createInput(array(
				'name' => 'displayName',
				'required' => true,
				'validators' => array(
					array(
						'name' => 'Int',
						'options' => array(
							'min' => 1,
							'max' => 50,
						),
					),
				),
			)));
			
			$this->inputFilter = $inputFilter;
		}
		
		return $this->inputFilter;
	}
	
}