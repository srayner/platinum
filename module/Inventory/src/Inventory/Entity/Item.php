<?php

namespace Inventory\Entity;

use Doctrine\ORM\Mapping as ORM,
    Zend\InputFilter\InputFilter,
    Zend\InputFilter\Factory as InputFactory;

/**
 * An inventory item.
 *
 * @ORM\Entity
 * @ORM\Table(name="sc_items")
 */
class Item extends Entity
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer");
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=24)
	 */
	protected $item_code;

	/**
	 * @ORM\Column(type="string", length=128)
	 */
	protected $short_description;
	
	/**
     * @ORM\ManyToOne (targetEntity="Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
	 */
	protected $category;
	
	/**
	 * @ORM\Column(type="integer");
	 */
	protected $stock_qty;
	
	/**
	 * @ORM\Column(type="integer") @ORM\Version
	 * @var unknown_type
	 */
	protected $version;
	
	/**
	 * Populate from an array.
	 *
	 * @param array $data
	 */
	public function populate($data = array())
	{
		$this->id = $data['id'];
		$this->item_code = $data['item_code'];
		$this->short_description = $data['short_description'];
	}
	
	public function getInputFilter()
	{
		if (!$this->inputFilter)
		{
			$inputFilter = new InputFilter();
			$factory = new InputFactory();
	
			// Id filter.
			$inputFilter->add($factory->createInput(array(
					'name' => 'id',
					'required' => true,
					'filters' => array(
							array('name' => 'Int'),
					),
			)));
	
			// Item Code filter.
			$inputFilter->add($factory->createInput(array(
					'name' => 'item_code',
					'required' => true,
					'filters' => array(
						array('name' => 'StripTags'),
						array('name' => 'StringTrim'),
						array('name' => 'StringToUpper')
					),
					'validators' => array(
						array(
							'name' => 'StringLength',
							'options' => array(
								'encoding' => 'UTF-8',
								'min' => 1,
								'max' => 24,
							),
						),
					),
			)));
	
			// Short Description filter.
			$inputFilter->add($factory->createInput(array(
				'name' => 'short_description',
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