<?php

namespace Helper;

use Entity\Pricing;
use Doctrine\ORM\EntityManager;

class PricingHelper{
	
	public static function Create(EntityManager $em = null){
		
		$pricing = new Pricing(
			PricingData::Duration(), 
			PricingData::FlatPrice(), 
			PricingData::MaxWord(), 
			PricingData::WordPrice());
		
		$pricing->setActive();
		$pricing->setName(PricingData::Name());
		$pricing->setDescription($pricing->getName());
		
		if($em != null){
			$em->persist($pricing);
			$em->flush($pricing);
		}
		
		return $pricing;
	}
	
}


class PricingData{
	
	
	
	public static function Name(){	return self::Random(self::$name);	}
	private static $name = array(
		'SuperFast',
		'Normal',
		'Quality'
	);
	
	public static function Duration(){	return self::Random(self::$duration);	}
	private static $duration = array(
		7,
		14,
		21
	);
	
	public static function FlatPrice(){	return self::Random(self::$flatPrice);	}
	private static $flatPrice = array(
		10,
		50,
		100
	);
	
	public static function MaxWord(){	return self::Random(self::$maxWord);	}
	private static $maxWord = array(
		1000,
		10000,
		100000
	);
	
	public static function WordPrice(){	return self::Random(self::$wordPrice);	}
	private static $wordPrice = array(
		0.01,
		0.02,
		0.05
	);
	
	private static function Random(array $data){
		$key = array_rand($data);
		return $data[$key];
	} 
}