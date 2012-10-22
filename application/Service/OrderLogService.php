<?php

namespace Service;

use Entity\OrderLog;
use Entity\Order;
use Entity\User;
use Entity\Proofreader;
use Entity\Correction;

use Core\Service\ServiceBase;


class OrderLogService extends ServiceBase
{
	public function _setupAcl(){
	}

	
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
	 * Intervention Service specific logs
	 */
	public function orderForceClosed($publicComment)
	{
		$user = $this->getContext()->getUser();
		$order = $this->getContext()->getOrder();
		$comment = "Admin [" . $user->getId() . "] force-closed the order [" . $order->getId() . "] with the comment [" . $publicComment . "]";
		$log = new OrderLog(OrderLog::ORDER_FORCECLOSED, $comment, $order);
		$this->persist($log);
	}
	
	public function orderInRework($publicComment)
	{
		$user = $this->getContext()->getUser();
		$order = $this->getContext()->getOrder();
		$proofreader = $order->getProofreader();
		$comment = "Admin [" . $user->getId() . "] submitted the order [" . $order->getId() . "] to rework by Proofreader [" . $proofreader->getId() . "] with the comment [" . $publicComment . "]";
		$log = new OrderLog(OrderLog::ORDER_REWORK, $comment, $order);
		$this->persist($log);
	}
	
	public function orderRevoked($publicComment)
	{
		$user = $this->getContext()->getUser();
		$order = $this->getContext()->getOrder();
		$proofreader = $order->getProofreader();
		$comment = "Admin [" . $user->getId() . "] revoked the order [" . $order->getId() . "] from Proofreader [" . $proofreader->getId() . "] with the comment [" . $publicComment . "]";
		$log = new OrderLog(OrderLog::ORDER_REVOKED, $comment, $order);
		$this->persist($log);
	}
	
	public function orderReopened()
	{
		$user = $this->getContext()->getUser();
		$order = $this->getContext()->getOrder();
		$comment = "Admin [" . $user->getId() . "] reopened the order [" . $order->getId() . "]";
		$log = new OrderLog(OrderLog::ORDER_REOPENED, $comment, $order);
		$this->persist($log);
	}
	
	
	
	
	
	
	
	
	
}