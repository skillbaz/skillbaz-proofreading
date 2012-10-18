<?php

namespace Helper;

use Entity\Member;

use Entity\Firm;

use Entity\User;
use Doctrine\ORM\EntityManager;

class FirmHelper{
	
	public static function CreateWithMember(User $user, EntityManager $em = null){
		
		$address = AddressHelper::Create($em);
		$firm = new Firm($address);
		$firm->setName(FirmData::Name());
		$firm->setDescription($firm->getName());
		
		$member = new Member($user, $firm);
		$member->setRole(Member::MEMBER);
		
		if($em != null){
			$em->persist($firm);
			$em->flush($firm);
			
			$em->persist($member);
			$em->flush($member);
			
			$em->refresh($address);
			$em->refresh($user);
			$em->refresh($firm);
		}
		
		return $firm;
	}
	
	
	public static function CreateWithBoss(User $user, EntityManager $em = null){
	
	$address = AddressHelper::Create($em);
	$firm = new Firm($address);
	$firm->setName(FirmData::Name());
	$firm->setDescription($firm->getName());
	
	$member = new Member($user, $firm);
	$member->setRole(Member::BOSS);
	
	if($em != null){
	$em->persist($firm);
		$em->flush($firm);
			
		$em->persist($member);
		$em->flush($member);
			
		$em->refresh($address);
		$em->refresh($user);
		$em->refresh($firm);
	}
	
		return $firm;
	}
	
}


class FirmData{
	
	public static function Name(){	return self::Random(self::$name);	}
	private static $name = array(
		'Baymore AG',
		'Payless GmbH',
		'Skillbaz GmbH'
	);
	
	
	private static function Random(array $data){
		$key = array_rand($data);
		return $data[$key];
	} 
}