<?php

namespace Service;

use Core\Acl\Acl;

use Entity\User;
use Entity\Address;
use Entity\Legi;

use Core\Service\ServiceBase;
use Core\Service\Params;



class UserService
	extends ServiceBase
{
	/**
	 * @var Repository\OrderRepository
	 * @Inject Repository\OrderRepository
	 */
	private $orderRepo;
	
	/**
	 * @var Repository\UserRepository
	 * @Inject Repository\UserRepository
	 */
	private $userRepo;
	
	
	public function _setupAcl(){
		$this->acl->allow(Acl::USER, $this, 'deleteUser');
		$this->acl->allow(Acl::USER, $this, 'updateUser');
		$this->acl->allow(Acl::USER, $this, 'updateAddress');
		$this->acl->allow(Acl::USER, $this, 'updateLegi');
		$this->acl->allow(Acl::ADMIN, $this, 'activateUser');
		$this->acl->allow(Acl::ADMIN, $this, 'deactivateUser');
	}
	
	
	/**
	 * Creates a new user entity
	 */
	public function createUser(Params $params)
	{
		//Create new user entity
		$user = new User();
		$this->persist($user);
		
		//Update the relevant information
		$this->updateUser($params);
	}
	
	
	/**
	 * Deletes a user if he has no open orders
	 */
	public function deleteUser()
	{
		$user = $this->getContext()->getUser();
		
		//Check whether the user has still open orders
		$openOrders = $this->orderRepo->findOpenOrders($user);
		if(!empty($openOrders)){
			//Error: There are still open orders
		}
		
		$this->remove($user);
	}
	
	
	/**
	 * Updates the information of an existing user
	 */
	public function updateUser(Params $params)
	{
		//Get the user from the context and set up a mail validator
		$user = $this->getContext()->getUser();
		$mailValidator = new \Zend_Validate_EmailAddress();
		
		//Check and update the firstname
		$firstname = $params->getValue('firstname');
		if(null == $firstname){
			$params->addMessage('firstname', 'Please provide a valid firstname');
			$this->validationFailed();
		}
		elseif($firstname != $user->getFirstname()){
			$user->setFirstname($firstname);
		}
		
		//Check and update the surname
		$surname = $params->getValue('surname');
		if(null == $surname){
			$params->addMessage('surname', 'Please provide a valid surname');
			$this->validationFailed();
		}
		elseif($surname != $user->getSurname()){
			$user->setSurname($surname);
		}

		//Check and update the email address
		$email = $params->getValue('email');
		if(!$mailValidator->isValid($email)){
			$params->addMessage('email', 'Please provide a valid email address');
			$this->validationFailed();
		}
		elseif($email != $user->getEmail()){
			$user->setEmail($email);
		}
		
		//Check and update the skype address
		$skype = $params->getValue('skype');
		if(null == $skype){
			$params->addMessage('skype', 'Please provide a valid skype address');
			$this->validationFailed();
		}
		elseif($skype != $user->getSkype()){
			$user->setSkype($skype);
		}
		
		//Check and update the preferred language
		$preflanguage = $params->getValue('preflanguage');
		if(null == $preflanguage){
			$params->addMessage('preflanguage', 'Please provide a valid language');
			$this->validationFailed();
		}
		elseif($preflanguage != $user->getPrefLanguage()){
			$user->setPrefLanguage($preflanguage);
		}
	}
	
	
	/**
	 * Updates or creates an Address Entity of a User
	 */
	public function updateAddress(Params $params)
	{
		//Get the user and his respective address from the context or create one
		$user = $this->getContext()->getUser();
		if(null != $user->getAddress()){
			$address = $user->getAddress();
		}
		else{
			$address = new Address();
			$user->setAddress($address);
			$this->persist($address);
		}
		
		//Check and update the street
		$street = $params->getValue('street');
		if(null == $street){
			$params->addMessage('street', 'Please provide a valid street');
			$this->validationFailed();
		}
		elseif($street != $address->getStreet()){
			$address->setStreet($street);
		}
		
		//Check and update the zipcode
		$zipcode = $params->getValue('zipcode');
		if(null == $zipcode){
			$params->addMessage('zipcode', 'Please provide a valid zipcode');
			$this->validationFailed();
		}
		elseif($zipcode != $address->getZipcode()){
			$address->setZipcode($zipcode);
		}
		
		//Check and update the city
		$city = $params->getValue('city');
		if(null == $city){
			$params->addMessage('city', 'Please provide a valid city');
			$this->validationFailed();
		}
		elseif($city != $address->getCity()){
			$address->setCity($city);
		}
		
		//Check and update the country
		$country = $params->getValue('country');
		if(null == $country){
			$params->addMessage('country', 'Please provide a valid country');
			$this->validationFailed();
		}
		elseif($country != $address->getCountry()){
			$address->setCountry($country);
		}
	}
	
	
	/**
	 * Create or update a legi and its information
	 * @param Params $params
	 */
	public function updateLegi(Params $params)
	{
		//Get the user from the context
		$user = $this->getContext()->getUser();
		
		//Check whether he already has a legi
		if(null == $user->getLegi()){
			//If not, create a new Legi Entity, link it to the user and persist it
			$legi = new Legi();
			$user->setLegi($legi);
			$this->persist($legi);
		}
		//If yes, load it
		else{
			$legi = $user->getLegi();
		}
		
		//Check and update the legi number
		$legiNumber = $params->getValue('legiNumber');
		if(null == $legiNumber){
			$params->addMessage('legiNumber', 'Please provide a valid legi number');
			$this->validationFailed();
		}
		elseif($legiNumber != $legi->getLegiNumber()){
			$legi->setLegiNumber($legiNumber);
		}
		
		//Check and update the university
		$university = $params->getValue('university');
		if(null == $university){
			$params->addMessage('university', 'Please provide a valid university');
			$this->validationFailed();
		}
		elseif($university != $legi->getUniversity()){
			$legi->setUniversity($university);
		}
		
		//Check and update the validity
		$validity = $params->getValue('validity');
		if(null == $validity){
			$params->addMessage('validity', 'Please enter a validity date');
			$this->validationFailed();
		}
		$legi->setValidity($validity);
	}
	
	
	/**
	 * Activate a user
	 */
	public function activateUser($userId)
	{
		$user = $this->userRepo->find($userId);
		$user->setActive();
	}
	
	
	/**
	 * Deactivate a user
	 */
	public function deactivateUser($userId)
	{
		$user = $this->userRepo->find($userId);
		$user->setActive(false);
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