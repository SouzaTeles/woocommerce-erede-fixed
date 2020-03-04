<?php

namespace erede\common;

/**
* Class BaseModel
* 
* This class is used to help conversion methods.
*/
class BaseModel
{
	/**
	 * Convert object to JSON
	 * @return string
	 */
	public function toJson() {
		$array = $this->mapModelToArray($this);
		if (empty($array))
		{
			return '';
		}
		return json_encode($array);
	}
	
	/**
	 * Convert object to array
	 * @return array
	 */
	public function toArray() {
		return $this->mapModelToArray($this);
	}
    
	/**
	 * Convert entity model into array
	 * @param mixed $entity
	 * @param int $recursionDepth
	 */
    private function mapModelToArray($entity, $recursionDepth = 2) {
    	$result = array();
    	
    	if (is_array($entity)) {
    		foreach ($entity as $item) {
    			$result[] = $this->mapModelToArray($item, $recursionDepth - 1);
    		}
    	}
    	else {
	    	$class = new \ReflectionClass(get_class($entity));
	    	foreach ($class->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
	    		$methodName = $method->name;
	    		if (strpos($methodName, 'get') === 0 && strlen($methodName) > 3) {
	    			$propertyName = lcfirst(substr($methodName, 3));
	    			$value = $method->invoke($entity);
	    
	    			if (is_object($value) || is_array($value)) {
	    				if ($recursionDepth > 0) {
	    					$result[$propertyName] = $this->mapModelToArray($value, $recursionDepth - 1);
	    				}
	    			}
	    			elseif ($value !== null) {
	    				$result[$propertyName] = $value;
	    			}
	    		}
	    	}
    	}
    	
    	return $result;
    }
}