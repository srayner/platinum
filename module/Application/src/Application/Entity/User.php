<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use ZfcUser\Entity\UserInterface;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * An application user.
 *
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends Entity implements UserInterface
{
	protected $inputFilter;
	
	/**
	 * @var int
	 * @ORM\Id
	 * @ORM\Column(name="user_id", type="integer");
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $username;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $email;
	
	/**
	 * @var string
	 * @ORM\Column(name="display_name", type="string", length=50, nullable=true)
	 */
	protected $displayName;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", length=128)
	 */
	protected $password;
	
	/**
	 * Get id.
	 *
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}
	
	/**
	 * Set id.
	 *
	 * @param int $id
	 * @return UserInterface
	 */
	public function setId($id)
	{
		$this->id = (int) $id;
		return $this;
	}
	
	/**
	 * Get username.
	 *
	 * @return string
	 */
	public function getUsername()
	{
		return $this->username;
	}
	
	/**
	 * Set username.
	 *
	 * @param string $username
	 * @return UserInterface
	 */
	public function setUsername($username)
	{
		$this->username = $username;
		return $this;
	}
	
	/**
	 * Get email.
	 *
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}
	
	/**
	 * Set email.
	 *
	 * @param string $email
	 * @return UserInterface
	 */
	public function setEmail($email)
	{
		$this->email = $email;
		return $this;
	}
	
	/**
	 * Get displayName.
	 *
	 * @return string
	 */
	public function getDisplayName()
	{
		return $this->displayName;
	}
	
	/**
	 * Set displayName.
	 *
	 * @param string $displayName
	 * @return UserInterface
	 */
	public function setDisplayName($displayName)
	{
		$this->displayName = $displayName;
		return $this;
	}
	
	/**
	 * Get password.
	 *
	 * @return string
	 */
	public function getPassword()
	{
		return $this->password;
	}
	
	/**
	 * Set password.
	 *
	 * @param string $password
	 * @return UserInterface
	 */
	public function setPassword($password)
	{
		$this->password = $password;
		return $this;
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
	
			// Username.
			$inputFilter->add($factory->createInput(array(
				'name' => 'username',
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
							'min' => 3,
							'max' => 255,
						),
					),
				),
			)));
	
			// Password
			$inputFilter->add($factory->createInput(array(
					'name' => 'password',
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
			
			// Email
			$inputFilter->add($factory->createInput(array(
					'name' => 'email',
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
											'max' => 255,
									),
							),
					),
			)));
			
			// Display name
			$inputFilter->add($factory->createInput(array(
					'name' => 'displayName',
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