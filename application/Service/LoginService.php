<?php

namespace Service;

use Entity\Login;

use Core\Acl\Acl;
use Core\Service\ServiceBase;


class LoginService extends ServiceBase
{
	/**
	 * @var Service\UserService
	 * @Inject Service\UserService
	 */
	private $userService;
	
	
	public function _setupAcl(){
		$this->acl->allow(Acl::GUEST, 	$this, 'login');
		$this->acl->allow(Acl::GUEST, 	$this, 'passwordLost');
		$this->acl->allow(Acl::GUEST, 	$this, 'resetPassword');
		$this->acl->allow(Acl::USER, 	$this, 'logout');
		$this->acl->allow(Acl::USER, 	$this, 'changePassword');
	}
	
	
	public function createLogin($password, User $user){
		//Create a new login instance
		$login = new Login();
		
		//Check the length of the password
		if(8 >= strlen($password)){
			$this->validationFailed(true, 'Password must consist of at least 8 characters');
		}
		
		//Set the user and the respective password
		$login->setNewPassword($password);
		$login->setUser($user);
		
		//Persist the login instance
		$this->persist($login);
	}
	
	
	public function login($identifier, $password){
		/** @var Entity\User */
		$user = $this->userService->getUserByIdentifier($identifier);
		
		$authAdapter = new \Core\Auth\Adapter($user, $password);
		$result = \Zend_Auth::getInstance()->authenticate($authAdapter);
		
		return $result;
	}
	
	
	public function logout(){
		\Zend_Auth::getInstance()->clearIdentity();
	}
	
	
	public function passwordLost($identifier){
		/** @var Entity\User */
		$user = $this->userService->getUserByIdentifier($identifier);
		
		if(is_null($user)){
			return false;
		}
		
		$login = $user->getLogin();
		
		if(is_null($login)){
			return false;
		}
		
		$email = $user->getEmail();
		$resetKey = $login->createPwResetKey();
		
		// TODO: Send Mail (to $email) with Link (containing $resetKey) to Reset Password.
		
		
		return true;
	}
	
	
	public function resetPassword($pwResetKey, $password){
		
		$login = $this->getLoginByResetKey($pwResetKey);
		
		if(is_null($login)){
			$this->addValidationMessage("No Login found for given PasswordResetKey");
		}
		
		$login->setNewPassword($password);
		$login->clearPwResetKey();
	}
	
	
	/**
	 * Returns the LoginEntity with the given pwResetKey
	 *
	 * @param string $pwResetKey
	 * @return Entity\Login
	 */
	private function getLoginByResetKey($pwResetKey)
	{
		/** @var \Entity\Login $login */
		$login = $this->em->getRepository('Entity\Login')->findOneBy(array('pwResetKey' => $pwResetKey));
	
		return $login;
	}
}