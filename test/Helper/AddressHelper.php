<?php

namespace Helper;

use Entity\Address;
use Doctrine\ORM\EntityManager;

class AddressHelper{
	
	public static function Create(EntityManager $em = null){
		
		$address = new Address();
		
		$address->setStreet(AddressData::Street());
		$address->setZipcode(AddressData::Zipcode());
		$address->setCity(AddressData::City());
		$address->setCountry(AddressData::Country());
		
		if($em != null){
			$em->persist($address);
			$em->flush($address);
		}
		
		return $address;
	}
}


class AddressData{
	
	public static function Street(){	return self::Random(self::$street);	}
	private static $street = array(
		'Baselstrasse 13',
		'Bernstrasse 41',
		'Oberrüti 119'
	);
	
	
	public static function Zipcode(){	return self::Random(self::$zipcode);	}
	private static $zipcode = array(
		'CH-5543',
		'1256',
		'DE-90453'
	);
	
	
	public static function City(){	return self::Random(self::$city);	}
	private static $city = array(
		'London',
		'New York',
		'Lutown'
	);
	
	
	public static function Country(){	return self::Random(self::$country);	}
	private static $country = array(
		'Switzerland',
		'France',
		'India'
	);
	
	
	
	private static function Random(array $data){
		$key = array_rand($data);
		return $data[$key];
	} 
}