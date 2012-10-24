<?php

namespace Service;

use Entity\User;

use Entity\Member;

use Entity\Address;

use Entity\Firm;

use Core\Acl\Acl;

use Core\Service\ServiceBase;
use Core\Service\Params;


class FirmService extends ServiceBase
{
	/**
	 * @var Repository\OrderRepository
	 * @Inject Repository\OrderRepository
	 */
	private $orderRepo;
	
	
	public function _setupAcl(){
		$this->acl->allow(Acl::USER, $this, 'createFirm');
		$this->acl->allow(Acl::BOSS, $this, 'addUser');
		$this->acl->allow(Acl::BOSS, $this, 'inviteStranger');
		$this->acl->allow(Acl::BOSS, $this, 'editMember');
		$this->acl->allow(Acl::BOSS, $this, 'kickMember');
		$this->acl->allow(Acl::BOSS, $this, 'deleteFirm');
		$this->acl->allow(Acl::BOSS, $this, 'updateFirm');
		$this->acl->allow(Acl::MEMBER, $this, 'leaveFirm');
	}
	
	/**
	 * Create a new firm
	 */
	public function createFirm()
	{
		//Create a new address and firm instance
		$address = new Address();
		$firm = new Firm($address);
		
		//Persist them
		$this->persist($address);
		$this->persist($firm);
	}
	
	/**
	 * Add a certain user as member to the firm
	 */
	public function addUser(User $user)
	{
		//Create a new member instance
		$firm = $this->getContext()->getFirm();
		$member = new Member($user, $firm);
		
		//Set the role of the member as member
		$member->setRole(Member::MEMBER);
		
		//Persist the instance
		$this->persist($member);
	}
	
	/**
	 * Invite a stranger as member of the firm
	 */
	public function inviteStranger(Params $params)
	{
		//Set up a mail validator and get the firm from the context
		$mailValidator = new \Zend_Validate_EmailAddress();
		$firm = $this->getContext()->getFirm();
		
		//Create a new user entity
		$user = new User();
		
		//Check surname from params and set it if passed
		$surname = $params->getValue('surname');
		if(null == $surname){
			$params->addMessage('surname', 'Please provide a surname');
			$this->validationFailed();
		}
		$user->setSurname($surname);
		
		//Check firstname from params and set it if passed
		$firstname = $params->getValue('firstname');
		if(null == $firstname){
			$params->addMessage('firstname', 'Please provide a firstname');
			$this->validationFailed();
		}
		$user->setFirstname($firstname);
		
		//Check email from params and set it if passed
		$email = $params->getValue('email');
		if(!$mailValidator->isValid($email)){
			$params->addMessage('email', 'Please provide a valid E-Mail address');
			$this->validationFailed();
		}
		$user->setEmail($email);
		
		//Deactivate the new user (reactivated when invitation is accepted)
		$user->setActive(false);
		
		//Create a new member entity
		$member = new Member($user, $firm);
		
		//Persist the new instances
		$this->persist($user);
		$this->persist($member);
		
			//To do: Send Mail with link for shortened registration
	}
	
	/**
	 * Edit the role of a certain member
	 */
	public function editMember(Member $member, $role)
	{
		//Check whether the provided role is valid
		if(($role != Member::BOSS) && ($role != Member::MEMBER)){
			$this->validationFailed(true, 'Invalid role');
		}
		
		//Check if the specific role is already set and change it if necessary
		if( $role != $member->getRole()){
			$member->setRole($role);
		}
	}
	
	/**
	 * Kick a member from the firm
	 */
	public function kickMember(Member $member)
	{
		//Get the firm from the context
		$firm = $this->getContext()->getFirm();
		
		//Check whether the member belongs to the respective firm
		if( $firm != $member->getFirm()){
			$this->validationFailed(true, 'Invalid member');
		}
		else{
			$this->remove($member);
		}
	}
	
	
	/**
	 * Delete a firm
	 */
	public function deleteFirm()
	{
		//Get the firm, address and its respective members from the context
		$firm = $this->getContext()->getFirm();
		$members = $firm->getMembers();
		$address = $firm->getAddress();
		
		//Search for open orders which point to the selected address
		$openOrders = $this->orderRepo->findOpenOrdersByAddress($address);
		
		//If there are any open orders, produce error
		if(!empty($openOrders)){
			$this->validationFailed(true, 'There are still open orders');
		}
		
		//If not, delete all the members
		foreach($members as $member){
			$this->remove($member);
		}
		
		//And finally, remove the firm (the address is kept because it is referenced by the order)
		$this->remove($firm);
	}
	
	/**
	 * Update the information of a firm
	 */
	public function updateFirm(Params $params)
	{
		$firm = $this->getContext()->getFirm();
		
		//Check name from params and set it if passed
		$name = $params->getValue('name');
		if(null == $name){
			$params->addMessage('name', 'Please provide a name');
			$this->validationFailed();
		}
		elseif($name != $firm->getName()){
			$firm->setName($name);
		}
				
		//Check description from params and set it if passed
		$description = $params->getValue('description');
		if(null == $description){
			$params->addMessage('description', 'Please provide a description');
			$this->validationFailed();
		}
		elseif($description != $firm->getDescription()){
			$firm->setDescription($description);
		}
		
		//$address // In seperater Funktion machen!
	}
	
	
	/**
	 * Leave a firm
	 */
	public function leaveFirm()
	{
		$user = $this->getContext()->getUser();
		//$user->getMembers() blabla
	}
	
	
}