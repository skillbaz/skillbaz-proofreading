<?php

namespace Helper;

use Entity\User;
use Doctrine\ORM\EntityManager;

class UserHelper{
	
	public static function Create(EntityManager $em = null){
		
		$user = new User();
		$user->setFirstname(UserData::Firstname());
		$user->setSurname(UserData::Surname());
		$user->setEmail(UserData::Email());
		$user->setPrefLanguage(UserData::Language());
		
		$user->setActive();
		
		$user->setAddress(AddressHelper::Create($em));
		if($em != null){
			$em->persist($user);
			$em->flush($user);
			$em->refresh($user->getAddress());
		}
		
		return $user;
	}
	
}


class UserData{
	
	public static function Firstname(){	return self::Random(self::$firstnames);	}
	private static $firstnames = array(
		'Hans',
		'Peter',
		'Paul'
	);
	
	
	public static function Surname(){	return self::Random(self::$surname);	}
	private static $surname = array(
		'Müller',
		'Meier',
		'Stalder'
	);
	
	
	public static function Email(){		return self::Random(self::$email);		}
	private static $email = array(
		'info@skillbaz.com',
		'proofreader@skillbaz.com',
		'customer@skillbaz.com'
	);
	
	
	public static function Language(){	return self::Random(self::$language);	}
	private static $language = array(
		'DE',
		'EN',
		'FR',
		'RU'
	);
	
	
	private static function Random(array $data){
		$key = array_rand($data);
		return $data[$key];
	} 
}