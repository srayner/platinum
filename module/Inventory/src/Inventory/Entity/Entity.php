<?php

namespace Inventory\Entity;

class Entity
{
	/**
	 * toArray method
	 */
	public function toArray()
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
	
				if (substr($class, 0, 8) == 'Doctrine')
				{
					$result[$key.'_id'] = $this->$key->id;
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
}