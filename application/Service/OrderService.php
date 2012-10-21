<?php

namespace Service;

use Core\Acl\Acl;

use Entity\User;
use Entity\Order;
use Entity\Document;
use Entity\Rating;
use Entity\AccountsReceivable;
use Entity\Discussion;
use Entity\OrderLog;
use Entity\Correction;

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
	 * @var Repository\ProofreaderRepository
	 * @Inject Repository\ProofreaderRepository
	 */
	private $proofreaderRepo;
	
	/**
	 * @var Repository\OrderRepository
	 * @Inject Repository\OrderRepository
	 */
	private $orderRepo;
	
	/**
	 * @var Repository\CorrectionRepository
	 * @Inject Repository\CorrectionRepository
	 */
	private $correctionRepo;
	
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
	
	/**
	 * @var Service\RatingService
	 * @Inject Service\RatingService
	 */
	private $ratingService;
	
	/**
	 * @var Service\OrderLogService
	 * @Inject Service\OrderLogService
	 */
	private $logService;
	
	
	
	public function _setupAcl(){
		$this->acl->allow(Acl::USER, $this, 'getOffers');
		$this->acl->allow(Acl::USER, $this, 'createOrder');
		$this->acl->allow(Acl::CUSTOMER, $this, 'acceptOrder');
		$this->acl->allow(Acl::CUSTOMER, $this, 'cancelOrder');
		$this->acl->allow(Acl::CUSTOMER, $this, 'acceptCorrection');
		$this->acl->allow(Acl::CUSTOMER, $this, 'rejectCorrection');
	}
	
	
	/**
	 * Returns an array of allowed pricings
	 * 
	 * @param Document $document
	 * @return array
	 */
	public function getOffers(Document $document)
	{
		//Count the number of words of the selected document
		$wordCount = $this->documentService->countWords($document);
		
		//Find all possible pricing based on the number of words
		$allowedPricings = $this->pricingRepo->findAllowedPricings($wordCount);
		
		return $allowedPricings;
	}
	
	public function createOrder(Params $params){
		
		// The Order is created by the authenticated User
		$user = $this->getContext()->getUser();
		
		// Get the AddressEntity by the selected AddressId
		// The AddressService will only return a AddressEntity if
		// the authenticated User is allowed to select this Address
		$addressId = $params->getValue('address');
		$address = $this->addressService->getAddress($addressId);
		
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
		
		//Create Log Entry
		$this->logService->offerCreated($order);
		
		return $order;
	}
	
	
	/**
	 * Changes the state of the order to open and sends an email to 
	 * all proofreaders with the needed ability
	 */
	public function acceptOrder()
	{
		//Get the respective order from the context
		$order = $this->getContext()->getOrder();
		
		//Check whether the state is offered
		if($order->getState() != Order::STATE_OFFERED){
			//Error: Order state not offered
		}
		
		//Set the state to open
		$order->setState(Order::STATE_OPEN);
		
		//Determine the proofreaders with a certain ability
		$recipients = $this->proofreaderRepo->findByAbility($order->getField());
		
			//send Mail -> to do
				
		//Create Log Entry
		$this->logService->offerAccepted();
	}
	
	
	/**
	 * Cancels the order while it's still open
	 */
	public function cancelOrder()
	{
		//Get the order from the context
		$order = $this->getContext()->getOrder();
		
		//Remove the order if the state is still open
		if($order->getState() != Order::STATE_OPEN){
			//Error: Order already in process. Cannot be deleted
		}
		
		//Create Log Entry
		$this->logService->orderCancelled();
		
		$this->remove($order);
	}
	
	
	/**
	 * Accept an uploaded correction
	 */
	public function acceptCorrection()
	{
		//Get the order from the context
		$order = $this->getContext()->getOrder();
		
		//Check if it has the status delivered
		if($order->getState() != Order::STATE_DELIVERED){
			//Error: Order not delivered
		}

		//Create a new rating entity with a nulled grade -> automatically generated
		$this->ratingService->createRating();
		
		//Create Log Entry
		$correction = $this->correctionRepo->getRecentCorrection($order);
		$this->logService->correctionAccepted($correction);
		
		//Close the order
		$this->closeOrder($orderId);
	}
	
	
	/**
	 * Reject a correction
	 */
	public function rejectCorrection($comment)
	{
		//Get the order from the context
		$order = $this->getContext()->getOrder();
		
		//Check whether the order is delivered
		if($order->getState() != Order::STATE_DELIVERED){
			//Error: Order not delivered
		}
		
		if($comment == ""){
			// Error: No comment submitted
		}
		
		//Determine the inputs for the discussion
		$user = $this->getContext()->getUser();
		$correction = $this->correctionRepo->getRecentCorrection($order);
		
		//Create the discussion
		$discussion = new Discussion($user, $correction, $comment);
		$this->persist($discussion);
		
			//To do: Send Mail to Admins
			
		//Create Log Entry
		$this->logService->correctionRejected($correction);
	}
	
	
	/**
	 * Close an order after the correction is finished and accepted - InService
	 */
	public function closeOrder()
	{
		//Get the order from the context
		$order = $this->getContext()->getOrder();
		
		//Set its state to closed
		$order->setState(Order::STATE_CLOSED);
		
		//Create new accounts receivables
		$accRec = new AccountsReceivable($order);
		$this->persist($accRec);
		
			//To do: Send Mail with Invoice
		
		//Save the most recent correction as final document
		$correction = $this->correctionRepo->getRecentCorrection($order);
		$document = $correction->getDocument();
		$order->setFinalDocument($document);
		
		//Remove the entities which are no longer needed
		$this->em->remove($order->getOriginalDocument());
		$order->setOriginalDocument(null);
				
		foreach($order->getCorrections() as $correction){
		$this->em->remove($correction);
		}
		$order->setCorrections(null);
		
		//Unlink the proofreader from the order
		$order->setProofreader(null);
	}
	
}