<?php

namespace Service;

use Entity\User;

use Core\Acl\Acl;
use Core\Service\Params;


use Core\Service\ServiceBase;


class RegisterService extends ServiceBase
{
	/**
	 * @var Service\UserService
	 * @Inject Service\UserService
	 */
	private $userService;
	
	/**
	 * @var Service\LoginService
	 * @Inject Service\LoginService
	 */
	private $loginService;
	
	/**
	 * @var Service\FirmService
	 * @Inject Service\FirmService
	 */
	private $firmService;
	
	/**
	 * @var Repository\UserRepository
	 * @Inject Repository\UserRepository
	 */
	private $userRepo;
	
	
	public function _setupAcl(){
		$this->acl->allow(Acl::GUEST, $this, 'register');
		$this->acl->allow(Acl::GUEST, $this, 'followInvitation');
		$this->acl->allow(Acl::GUEST, $this, 'activateInvited');
	}
	
	
	/**
	 * Register either as a private user or as a firm
	 */
	public function register(Params $params)
	{
		//Create a new user instance
		$user = $this->userService->createUser($params);
		
		//Create a new login with the provided password and user
		$password = $params->getValue('password');
		$this->loginService->createLogin($password, $user);
		
		//Check if the user wants to join as a private person or a firm
		$private = $params->getValue('private');
		switch ($private){
			case true:
				$this->userService->updateAddress($params);
				break;
			case false:
				$this->firmService->createFirm($params);
				$this->firmService->updateAddress($params);
				break;
			default:
				$params->addMessage('private', 'Please provide if you want to register as a private person');
				$this->validationFailed();
		}
		
		//Create the a-code to verify the e-mail address
		$acode = $user->createAcode();
			//To do: Send mail to user with Acode
	}
	
	
	/**
	 * Follow the invitation from other users
	 */
	public function followInvitation(Params $params, $userId)
	{
		//Get the user instance based on the id
		$user = $this->userRepo->find($userId);
		
		//Create a new login instance and link it to the user
		$password = $params->getValue('password');
		$this->loginService->createLogin($password, $user);
		
		//Update the users information
		$this->userService->updateUser($params);
		
		//Set the user as active
		$user->setActive();
	}
	
	/**
	 * Activate a user who has received an A-Code
	 */
	public function activateInvited($acode, $userId)
	{
		//Get the user instance
		$user = $this->userRepo->find($userId);
		
		//Check the activation code and activate the user if passed
		if($user->activate($acode)){
			$user->setActive();
		}
		else{
			$this->validationFailed(true, 'Invalid activation code');
		}
	}
	
}















