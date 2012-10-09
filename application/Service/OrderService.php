<?php

namespace Service;

use Core\Acl\Acl;

use Entity\User;
use Entity\Order;
use Entity\Document;
use Entity\Rating;
use Entity\AccountsReceivable;
use Entity\Discussion;

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
		$this->acl->allow(Acl::USER, $this, 'getOffers');
		$this->acl->allow(Acl::USER, $this, 'createOrder');
		$this->acl->allow(Acl::CUSTOMER, $this, 'acceptOrder');
		$this->acl->allow(Acl::CUSTOMER, $this, 'createRating');
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
	public function getOffers($document)
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
		
		//Set the state to open
		$order->setState(Order::STATE_OPEN);
		
		//Determine the proofreaders with a certain ability
		$recipients = $this->proofreaderRepo->findByAbility($order->getField());
		
			//send Mail -> to do
	}
	
	
	/**
	 * Cancels the order while it's still open
	 */
	public function cancelOrder($orderId)
	{
		//Determine the respective order
		$order = $this->orderRepo->find($orderId);
		
		//Remove the order if the state is still open
		if($order->getState() == Order::STATE_OPEN){
		$this->remove($order);
		}
			//Error: Order already in process. Cannot be deleted
	}
	
	
	/**
	 * Accept an uploaded correction
	 */
	public function acceptCorrection($orderId)
	{
		//Determine the respective order
		$order = $this->orderRepo->find($orderId);
		
		//Check if it has the status delivered
		if(!$order->getState() == Order::STATE_DELIVERED){
			//Error: Order not delivered
		}

		//Create a new rating entity with a nulled grade -> automatically generated
		$rating = new Rating($order->getProofreader(), $order);
		
		$this->persist($rating);
		
		//Close the order
		$this->closeOrder($orderId);
	}
	
	/**
	 * Create a rating for a closed order
	 */
	public function createRating($orderId, $grade)
	{
		//Determine the respective order
		$order = $this->orderRepo->find($orderId);
		
		//Check whether the order is closed
		if(!$order->getState() == Order::STATE_CLOSED){
			//Error: Order not yet closed
		}

		//Get the automatically generated rating entity
		$rating = $order->getRating();
		
		//Check whether grade is not yet set and if it is numeric, then set grade
		if($rating->getGrade() == null && is_numeric($grade)){
			$rating->setGrade($grade);
		}
		else{
			//Error: Grade already set or no valid input
		}
	}
	
	
	/**
	 * Reject a correction
	 */
	public function rejectCorrection($orderId, $comment)
	{
		//Determine the respective order
		$order = $this->orderRepo->find($orderId);
		
		//Check whether the order is delivered
		if(!$order->getState() == Order::STATE_DELIVERED){
			//Error: Order not delivered
		}
		
		//Determine the inputs for the discussion
		$user = $this->getContext()->getUser();
		$correction = $order->getCorrection();
		
		//Create the discussion
		$discussion = new Discussion($user, $correction, $comment);
		$this->persist($discussion);
		
			//To do: Send Mail to Admins
	}
	
	
	/**
	 * Close an order after the correction is finished and accepted - InService
	 */
	public function closeOrder($orderId)
	{
		//Determine the respective order
		$order = $this->orderRepo->find($orderId);
		
		//Set its state to closed
		$order->setState(Order::STATE_CLOSED);
		
		//Create new accounts receivables
		$accRec = new AccountsReceivable($order);
		$this->persist($accRec);
		
			//To do: Send Mail with Invoice
		
		//Save the most recent correction as final document
		$order->finalDocument = $order->getCorrection()->getDocument();
		
		//Remove the entities which are no longer needed
		$order->getOriginalDocument()->remove($document);
		$order->getCorrection()->remove($correction);
		$order->getDiscussion()->remove($discussion);
		
		//Unlink the proofreader from the order
		$order->proofreader = null;
	}
	

	
	
}