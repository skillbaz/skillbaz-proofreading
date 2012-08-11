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
	 * Updates a Address and returns it.
	 * 
	 * @param Address $address
	 * @param Params $params
	 * @return Entity\Address
	 */
	public function updateAddress(Address $address, Params $params){
		// ToDo
	}
	
}