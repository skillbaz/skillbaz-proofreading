<?php

namespace Helper;

use Entity\User;
use Entity\Field;
use Entity\Order;
use Doctrine\ORM\EntityManager;

class FieldHelper{
	
	public static function Create(EntityManager $em = null){
		
		$data = FieldData::Data();
		$field = new Field($data[0], $data[1]);
		$field->setActive();
		
		if($em != null){
			$em->persist($field);
			$em->flush($field);
		}
		
		return $field;
	}
	
}


class FieldData{
	
	public static function Data(){	return self::Random(self::$data);	}
	private static $data = array(
		array('Math', 'Mathemathics'),
		array('Mechanics', 'Mechanics'),
		array('Bio', 'Biology')
	);
	
	private static function Random(array $data){
		$key = array_rand($data);
		return $data[$key];
	} 
}