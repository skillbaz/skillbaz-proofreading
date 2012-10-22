<?php

namespace Service;

use Core\Acl\Acl;
use Entity\Order;
use Entity\Correction;
use Entity\Discussion;

use Core\Service\ServiceBase;


class InterventionService extends ServiceBase
{
	/**
	 * @var Service\OrderService
	 * @Inject Service\OrderService
	 */
	private $orderService;
	
	/**
	 * @var Service\OrderLogService
	 * @Inject Service\OrderLogService
	 */
	private $logService;
	
	/**
	 * @var Repository\CorrectionRepository
	 * @Inject Repository\CorrectionRepository
	 */
	private $correctionRepo;

	
	public function _setupAcl(){
		$this->acl->allow(Acl::ADMIN, $this, 'closeOrder');
		$this->acl->allow(Acl::ADMIN, $this, 'reworkCorrection');
		$this->acl->allow(Acl::ADMIN, $this, 'revokeOrder');
		$this->acl->allow(Acl::ADMIN, $this, 'reopenOrder');
	}
	
	
	/**
	 * Force-close an order
	 */
	public function closeOrder($publicComment, $price, $salary)
	{
		$order = $this->getContext()->getOrder();
		
		//Check whether the status is rejected
		if($order->getState() != Order::STATE_REJECTED){
			//Error: Order state not rejected
		}
		
		//Check whether the settled price is lower than the offered price and if it is numeric
		if($order->getOfferedPrice() > $price && is_numeric($price)){
			$order->setSettledPrice($price);
		}
		
		//Set the proofreader's salary
		$order->setProofreaderSalarySettled($salary);
		
		//Set the external commment
		$order->setPublicComment($publicComment);
		
		//Create log entry
		$this->logService->orderForceClosed($publicComment);			
		
		//Close the order
		$this->orderService->closeOrder();
	}
	
	
	/**
	 * Rework a correction by the same proofreader
	 * @param String $externalComment
	 * @param \DateTime $deadline
	 */
	public function reworkCorrection($publicComment, \DateTime $deadline)
	{
		$order = $this->getContext()->getOrder();
		
		//Check whether the order is rejected
		if($order->getState() != Order::STATE_REJECTED){
			//Error: Order not rejected
		}
		
		//Check whether this is the first iteration, if not - inform the admins
		if(0 < $order->getIteration()){
			//To do: Make an exclamation mark and alarm signals!
		}
		
		//Increase the iteration by 1
		$order->setIteration(++$order->getIteration());
		
		//Get the respective correction and the corresponding discussions
		$correction = $this->correctionRepo->getRecentCorrection($order);
		$discussions = $correction->getDiscussions();
		
		//Construct the internal comment for the proofreader and the admins by looping the discussions
		foreach($discussions as $discussion){
			//Evaluate who made the comment and make him anonymous
			switch ($discussion->getUser()){
				case $order->getUser(): $commentor = "Customer: ";
				case $order->getProofreader(): $commentor = "Proofreader: ";
				default: $commentor = "Admin: ";
			}
			$internalComment .= $discussion->createdAt() . "by " . $commentor . $discussion->getComment();
		}
		$order->setInternalComment($internalComment);
		
		//Set the public comment which the customer is able to see
		$order->setPublicComment($publicComment);
		
		//Set a new deadline
		$order->setDeadline($deadline);
		
		//Set the state of the order back to working
		$order->setState(Order::STATE_WORKING);
			
		//Create the log entry
		$this->logService->orderInRework($publicComment);
	}
	
	
	/**
	 * Revoke an order and set it back to open - take it away from the current proofreader
	 * @param String $internalComment
	 * @param \DateTime $deadline
	 */
	public function revokeOrder($publicComment, \DateTime $deadline, $proofreaderSalarySettled, $settledPrice)
	{
		$order = $this->getContext()->getOrder();
		
		//Check whether the order is rejected
		if($order->getState() != Order::STATE_REJECTED){
			//Error: Order not rejected
		}
		
		//Increase the iteration by 1
		$order->setIteration(++$order->getIteration());
		
		//Construct the internal comment for the proofreader and the admins by looping the discussions
		foreach($discussions as $discussion){
			//Evaluate who made the comment and make him anonymous
			switch ($discussion->getUser()){
				case $order->getUser(): $commentor = "Customer: ";
				case $order->getProofreader(): $commentor = "Proofreader: ";
				default: $commentor = "Admin: ";
			}
			$internalComment .= $discussion->createdAt() . "by " . $commentor . $discussion->getComment();
		}
		$order->setInternalComment($internalComment);
		
		//Set public comment for the customer
		$order->setPublicComment($publicComment);
		
		//Set the new deadline, salary and price
		$order->setDeadline($deadline);
		$order->setProofreaderSalarySettled($proofreaderSalarySettled);
		$order->setSettledPrice($settledPrice);
		
		//Set the state of the order back to open
		$order->setState(Order::STATE_OPEN);
			//To do: Send Mail to proofreaers
		
		//Create log entry
		$this->logService->orderRevoked($publicComment);
			
		$order->setProofreader(null);
	}
	
	/**
	 * Reopen an already taken order and remove the proofreader
	 */
	public function reopenOrder($proofreaderSalarySettled)
	{
		$order = $this->getContext()->getOrder();
		if($order->getState() != Order::STATE_WORKING){
			//Error: Order not in state 'working'.
		}
		
		//Set the new proofreader salary
		$order->setProofreaderSalarySettled($proofreaderSalarySettled);
		
		//Set the state back to open
		$order->setState(Order::STATE_OPEN);
			
		//Reset the proofreader
		$order->setProofreader(null);
		
			//To do: Send Mail to Proofreaders
		
		//Create log entry
		$this->logService->orderReopened();
	}
	
}