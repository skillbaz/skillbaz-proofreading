<?php

namespace Service;


use Core\Acl\Acl;

use Entity\User;
use Entity\Order;
use Entity\Document;

use Core\Service\Params;
use Core\Service\ServiceBase;


class OrderService
	extends ServiceBase
{
	
	/**
	 * @var Repository\PricingRepository
	 * @Inject Repository\PricingRepository
	 */
	private $pricingRepo;
	
	/**
	 * @var Repository\FieldRepository
	 * @Inject Repository\FieldRepository
	 */
	private $fieldRepo;
	
	
	
	/**
	 * @var Service\AddressService
	 * @Inject Service\AddressService
	 */
	private $addressService;
	
	/**
	 * @var Service\DocumentService
	 * @Inject Service\DocumentService
	 */
	private $documentService;
	
	
	
	public function _setupAcl(){
		$this->acl->allow(Acl::USER, $this, 'createOrder');
	}
	
	
	public function createOrder(Params $params){
		
		// The Order is created by the authenticated User
		$user = $this->getContext()->getUser();
		
		// Get the AddressEntity by the selected AddressId
		// The AddressService will only return a AddressEntity if
		// the authenticated User is allowed to select this Address
		$addressId = $params->getValue('address');
		$address = $this->addressService->get($addressId);
		
		// The DocumentService creates a new Document in the DB
		// and returns it... The required Paramd are given in the
		// $params (from the Form)
		$document = $this->documentService->createDocument($params);
		
		
		// Get the PricingEntity by the selected PricingId
		$pricingId = $params->getValue('pricing');
		$pricing = $this->pricingRepo->find($pricingId);
		
		// Check if pricing is active
		if(!$pricing->getActive()){
			// ERROR! Selected Pricing is inactive
		}
		
		// Check, if selected pricing is allowed (due to DocumentLength)
		if($document->getWordCount() > $pricing->getMaxWords()){
			// ERROR! Selected pricing not allowed for this
			// Document length
		}
		
		// Get the FieldEntity by the selected FieldId
		$fieldId = $params->getValue('field');
		$field = $this->fieldRepo->find($fieldId);
		
		if(!$field->getActive()){
			// ERROR! Selected Field is inactive
		}
		
		
		$order = new Order($user, $address, $document, $pricing, $field);
		$this->persist($order);
		
		return $order;
	}
	
	
}