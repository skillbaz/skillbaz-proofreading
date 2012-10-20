<?php

namespace Helper;

use Entity\Login;

use Entity\User;
use Doctrine\ORM\EntityManager;

class LoginHelper{
	
	public static function Create(EntityManager $em = null){
		
		$user = UserHelper::Create($em);
		$login = new Login();
		$login->setUser($user);
		$login->setNewPassword('1234');
		
		if($em != null){
			$em->persist($login);
			$em->flush($login);
			
			$em->refresh($user);
		}
		
		return $login;
	}
	
}
