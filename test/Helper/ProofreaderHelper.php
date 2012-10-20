<?php

namespace Helper;

use Entity\User;
use Entity\Proofreader;
use Doctrine\ORM\EntityManager;

class ProofreaderHelper{
	
	public static function Create(EntityManager $em = null){
		
		$user = UserHelper::Create($em);
		$proofreader = new Proofreader($user);
		$proofreader->setActive();
		
		if($em != null){
			$em->persist($proofreader);
			$em->flush($proofreader);
			$em->refresh($user);
		}
		
		return $proofreader;
	}
	
}
