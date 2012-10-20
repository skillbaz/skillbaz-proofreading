<?php

namespace Helper;

use Entity\Document;

use Entity\User;
use Entity\Field;
use Entity\Order;
use Doctrine\ORM\EntityManager;

class DocumentHelper{
	
	public static function Create(EntityManager $em = null){
		
		$document = new Document(
			DocumentData::Name(), 
			'desc', 
			__FILE__, 
			rand(1000, 1000000));
		
		if($em != null){
			$em->persist($document);
			$em->flush($document);
		}
		
		return $document;
	}
	
}


class DocumentData{
	
	public static function Name(){	return self::Random(self::$name);	}
	private static $name = array(
		'Bachelorarbeit',
		'Masterarbeit',
		'Vertrag'
	);
	
	
	
	private static function Random(array $data){
		$key = array_rand($data);
		return $data[$key];
	} 
}