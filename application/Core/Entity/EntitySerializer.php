<?php

namespace Core\Entity;


class EntitySerializer
{
	
	/**
	 * @var Doctrine\ORM\EntityManager
	 * @Inject Doctrine\ORM\EntityManager
	 */
	private $em;
	
	
	public function SerializeEntity(BaseEntity $entity){
		
		$class = get_class($entity);
		$meta = $this->em->getClassMetadata($class);

		$maxlen = max(array_map("strlen", $meta->fieldNames));
		
		$s = $class  . " {";
		
		foreach($meta->fieldNames as $column => $fieldName){
			
			$value = $meta->getFieldValue($entity, $fieldName);
			
			$s .= PHP_EOL . "  " . $fieldName . ": " . str_repeat(" ", $maxlen - strlen($fieldName));
			
			if(is_a($value, "DateTime")){	$s .= date_format($value, 'd.m.Y  H:i:s');	}
			elseif(is_scalar($value))	{	$s .= $value;	}
			else						{	$s .= str_replace(PHP_EOL, " ", print_r($value, true));	}
			
		}
		
		$s .= PHP_EOL . "}";
		
		return $s;
	}
	
}