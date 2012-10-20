<?php

namespace Service;

use Core\Acl\Acl;
use Entity\Order;
use Entity\Correction;

use Core\Service\ServiceBase;


class OrderService extends ServiceBase
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
		$this->acl->allow(Acl::RESPONSIBLE_PROOFREADER, $this, 'reworkCorrection');
		$this->acl->allow(Acl::ADMIN, $this, 'revokeOrder');
		$this->acl->allow(Acl::ADMIN, $this, 'reopenOrder');
	}
	
	
	/**
	 * Force-close an order
	 */
	public function closeOrder($comment, $price)
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
		
		//Create log entry
			//To do: Log (OrderLog noch im PR)
			
		$this->orderService->closeOrder();
	}
	
	
	/**
	 * Rework a correction by the same proofreader
	 * @param String $internalComment
	 * @param \DateTime $deadline
	 */
	public function reworkCorrection($internalComment, \DateTime $deadline)
	{
		$order = $this->getContext()->getOrder();
		
		//Check whether the order is rejected
		if($order->getState() != Order::STATE_REJECTED){
			//Error: Order not rejected
		}
		
		//Check whether this is the first iteration, if not - inform the admins about the rework
		if(0 < $order->getIteration()){
			//To do: Send Mail to admins
		}
		
		//Increase the iteration by 1
		$order->setIteration(++$order->getIteration());
		
		//Get the respective correction and the corresponding discussions
		$correction = $this->correctionRepo->getRecentCorrection($order);
		$discussions = $correction->getDiscussions();
		
		//Set the internal comment and add the deadline
		$order->setInternalComment($internalComment . " /Deadline: " . $deadline);
		
		//Set the discussions as public comment and add the deadline
		$order->setPublicComment($discussions . " /Deadline: " . $deadline);
		
		//Set the state of the order back to working
		$order->setState(Order::STATE_WORKING);
			
			//To do: Log Entry
	}
	
	
	/**
	 * Revoke an order and set it back to open - take it away from the current proofreader
	 * @param String $internalComment
	 * @param \DateTime $deadline
	 */
	public function revokeOrder($internalComment, \DateTime $deadline)
	{
		$order = $this->getContext()->getOrder();
		
		//Check whether the order is rejected
		if($order->getState() != Order::STATE_REJECTED){
			//Error: Order not rejected
		}
		
		//Increase the iteration by 1
		$order->setIteration(++$order->getIteration());
		
		//Set the internal comment and add the deadline
		$order->setInternalComment($internalComment);
		
		//Set the discussions as public comment and add the deadline
		$order->setPublicComment("Deadline: " . $deadline);
		
		//Set the state of the order back to open
		$order->setState(Order::STATE_OPEN);
			//To do: Send Mail to proofreaers
		
		//Create log entry
			// To do: Log (OrderLog noch im PR)
			
		$order->setProofreader(null);
	}
	
	/**
	 * Reopen an already taken order and remove the proofreader
	 */
	public function reopenOrder()
	{
		$order = $this->getContext()->getOrder();
		if($order->getState() != Order::STATE_WORKING){
			//Error: Order not in state 'working'.
		}
		
		//Set the state back to open
		$order->setState(Order::STATE_OPEN);
			
		//Reset the iteration and the proofreader
		$order->setIteration(0);
		$order->setProofreader(null);
		
			//To do: Send Mail to Proofreaders
		
		//Create log entry
			//To do: Log (OrderLog noch im PR)
	}
	
}