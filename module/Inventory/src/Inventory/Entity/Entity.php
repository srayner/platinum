<?php

namespace Inventory\Entity;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;

abstract class Entity implements InputFilterAwareInterface
{
    protected $inputFilter;
    
    /**
     * Magic method __get
     * @param string $property
     */
    public function __get($property)
    {
        // If a method exists to get the property...
        if(method_exists($this, 'get' . ucfirst($property)))
        {
            // ...then call it.
            return call_user_func(array($this, 'get' . ucfirst($property)));
        }
        else
        {
            // Otherwise return the property directly.
            return $this->$property;
        }
    }
    
    /**
     * Magic method __set
     * @param string $property
     * @param unknown_type $value
     */
    public function __set($property, $value)
    {
    
        // If a method exists to set the property...
        if(method_exists($this, 'set' . ucfirst($property)))
        {
            // ...call it.
            return call_user_func(array($this, 'set' . ucfirst($property)), $value);
        }
        else
        {
            // Otherwise set the property directly.
            $this->$property = $value;
        }
    }
    
	/**
	 * getArrayCopy method
	 */
	public function getArrayCopy()
	{
		$result = array();
		$properties = get_object_vars($this);
		foreach($properties as $key => $value)
		{
			if (is_object($this->$key))
			{
				$class = get_class($this->$key);
				if ($class == 'DateTime')
				{
					$result[$key] = $this->$key->format('Y-m-d H:i:s');
				}
	            else if (substr($class, 0, 8) == 'Doctrine')
	            {
	                $result[$key .'_id'] = $this->$key->__get('id');
	            }
			}
			elseif (is_null($this->$key))
			{
				$result[$key] = '';
			}
			else
			{
				$result[$key] = $value;
			}
	
		}
		return $result;
	}
	
	/**
	 * setInputFilter method
	 * @param InputFilterInterface $inputFilter
	 * @throws \Exception
	 */
	public function setInputFilter(InputFilterInterface $inputFilter)
	{
	    throw new \Exception("Not used");
	}
	
	/**
	 * getInputFilter method
	 */
	abstract public function getInputFilter();
}