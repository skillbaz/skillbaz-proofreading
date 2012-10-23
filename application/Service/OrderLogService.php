<?php

namespace Service;

use Entity\OrderLog;

use Core\Service\ServiceBase;


class OrderLogService extends ServiceBase
{
	public function _setupAcl(){
	}

	/**
	 * OrderService specific logs
	 */
	
	public function offerCreated(Order $order)
	{
		$user = $this->getContext()->getUser();
		$comment = "User [" . $user->getId() . "] received the order [" . $order->getId() . "] as an offer";
		$log = new OrderLog(OrderLog::OFFER_CREATED, $comment, $order);
		$this->persist($log);
	}
	
	public function offerAccepted()
	{
		$user = $this->getContext()->getUser();
		$order = $this->getContext()->getOrder();
		$comment = "User [" . $user->getId() . "] accepted the order [" . $order->getId() . "]";
		$log = new OrderLog(OrderLog::OFFER_ACCEPTED, $comment, $order);
		$this->persist($log);
	}
	
	public function orderCancelled()
	{
		$user = $this->getContext()->getUser();
		$order = $this->getContext()->getOrder();
		$comment = "User [" . $user->getId() . "] cancelled the order [" . $order->getId() . "]";
		$log = new OrderLog(OrderLog::ORDER_CANCELLED, $comment, $order);
		$this->persist($log);
	}
	
	public function correctionAccepted($correction)
	{
		$user = $this->getContext()->getUser();
		$order = $this->getContext()->getOrder();
		$comment = "User [" . $user->getId() . "] accepted the correction [" . $correction->getId() . "] from the order [" . $order->getId() . "]";
		$log = new OrderLog(OrderLog::CORRECTION_ACCEPTED, $comment, $order);
		$this->persist($log);
	}
	
	public function correctionRejected($correction)
	{
		$user = $this->getContext()->getUser();
		$order = $this->getContext()->getOrder();
		$comment = "User [" . $user->getId() . "] rejected the correction [" . $correction->getId() . "] from the order [" . $order->getId() . "]";
		$log = new OrderLog(OrderLog::CORRECTION_REJECTED, $comment, $order);
		$this->persist($log);
	}
	
	/**
	 * Proofreading Service specific Logs
	 */
	
	public function orderAccepted()
	{
		$proofreader = $this->getContext()->getProofreader();
		$order = $this->getContext()->getOrder();
		$comment = "Proofreader [" . $proofreader->getId() . "] accepted the order [" . $order->getId() . "]";
		$log = new OrderLog(OrderLog::ORDER_TAKEN, $comment, $order);
		$this->persist($log);
	}
	
	public function correctionUploaded($correction)
	{
		$proofreader = $this->getContext()->getProofreader();
		$order = $this->getContext()->getOrder();
		$comment = "Proofreader [" . $proofreader->getId() . "] uploaded the correction [" . $correction->getId() . "] of the order [" . $order->getId() . "]";
		$log = new OrderLog(Orderlog::CORRECTION_UPLOADED, $comment, $order);
		$this->persist($log);
	}
	
	public function correctionCommitted($correction)
	{
		$proofreader = $this->getContext()->getProofreader();
		$order = $this->getContext()->getOrder();
		$comment = "Proofreader [" . $proofreader->getId() . "] committed the correction [" . $correction->getId() . "] of the order [" . $order->getId() . "]";
		$log = new OrderLog(OrderLog::CORRECTION_COMMITTED, $comment, $order);
		$this->persist($log);
	}
	
	public function correctionReactionCommented($correction, $discussion)
	{
		$user = $this->getContext()->getUser();
		$order = $this->getContext()->getOrder();
		$comment = "User [" . $user->getId() . "] created the discussion [" . $discussion->getId() . "] on the correction [" . $correction->getId() . "] of the order [" . $order->getId() . "]";
		$log = new OrderLog(OrderLog::CORRECTION_COMMENTED, $comment, $order);
		$this->persist($log);
	}
	
}