<?php

namespace Service;


use Acl\Acl;

use Entity\User;
use Entity\Address;

use Core\Service\ServiceBase;


class AddressService
	extends ServiceBase
{
	
	public function _setupAcl(){
		
	}
	
	
	/**
	 * Returns an Address, if the authenticated User
	 * is allowed to see this address
	 * 
	 * @param string $addressId
	 * @return Entity\Address
	 */
	public function getAddress($addressId){
		// ToDo
	}
	
	
	/**
	 * Creates a new Address and returns it.
	 * 
	 * @param Params $params
	 * @return Entity\Address
	 */
	public function createAddress(Params $params){
		// ToDo
	}
	
	
	/**
	 * Updates an Address
	 * 
	 * @param Address $address
	 * @param Params $params
	 * @return Entity\Address
	 */
	public function updateAddress(Address $address, Params $params)
	{
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
	
}