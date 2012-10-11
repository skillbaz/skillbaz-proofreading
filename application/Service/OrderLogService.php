<?php

namespace Service;

use Entity\OrderLog;

use Core\Service\ServiceBase;


class OrderLogService extends ServiceBase
{
	
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
	
}