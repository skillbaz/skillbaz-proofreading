<?php

namespace Entity;

use Core\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="order_log")
 *
 */
class OrderLog extends BaseEntity
{
	
	const OFFER_CREATED 		= 	'new offer';
	const OFFER_ACCEPTED		=	'offer accepted';
	const ORDER_CANCELLED		=	'order cancelled';
	const ORDER_TAKEN			=	'order taken';
	const CORRECTION_UPLOADED 	=	'correction uploaded';
	const CORRECTION_COMMITTED 	= 	'correction committed';
	const CORRECTION_ACCEPTED 	=	'correction accepted';
	const CORRECTION_REJECTED 	= 	'correction rejected';
	const CORRECTION_COMMENTED 	= 	'correction commented';
	
	
	/**
	 * Time indicator of the event
	 * @ORM\Column(type="datetime", nullable=false)
	 */
	private $logTime;
	
	/**
	 * Type of the event
	 * @ORM\Column(type="string", length=64, nullable=false)
	 */
	private $logType;
	
	/**
	 * Description and additional details of the event
	 * @ORM\Column(type="text")
	 */
	private $comment;
	
	/**
	 * Corresponding order
	 * @var Entity\Order
	 * @ORM\ManyToOne(targetEntity="Order")
	 * @ORM\JoinColumn(name="order_id", referencedColumnName="id", nullable=false)
	 */
	private $order;
	
	
	/**
	 * @return datetime  
	 */
	public function getLogTime()
	{
		return $this->logTime;
	}
	public function setLogTime($logTime)
	{
		$this->logTime = $logTime;
	}
	
	/**
	 * @return string 
	 */
	public function getLogType()
	{
		return $this->logType;
	}
	public function setLogType($logType)
	{
		$this->logType = $logType;
	}
	
	/**
	 * @return text 
	 */
	public function getComment()
	{
		return $this->comment;
	}
	public function setComment($comment)
	{
		$this->comment = $comment;
	}
	
	/**
	 * @return Order
	 */
	public function getOrder()
	{
		return $this->order;
	}
	public function setOrder(Order $order)
	{
		$this->order = $order;
	}
	
}