<?php

namespace Sales\Entity;

abstract class AbstractEntity
{
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
    die(var_dump($property));
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
}
