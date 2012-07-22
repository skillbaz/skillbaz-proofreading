<?php

namespace Service;

use Acl\Acl;
use Entity\User;
use Core\Service\ServiceBase;


class UserService
	extends ServiceBase
{
	
	public function _setupAcl(){
		
	}
	
	
	/**
	 * @param Entity\User|string $identifier
	 * @return Entity\User
	 */
	public function getUserByIdentifier($identifier){
		
		$user = null;
		
		$mailValidator = new \Zend_Validate_EmailAddress();
		
		if($identifier instanceOf User)
		{
			$user = $identifier;
		}
		elseif($mailValidator->isValid($identifier))
		{
			$user = $this->em->getRepository('Entity\User')->findOneBy(array('email' => $identifier));
		}
		else
		{
			$user = $this->em->getRepository('Entity\User')->find($identifier);
		}
		
		return $user;
	}
	
	
}