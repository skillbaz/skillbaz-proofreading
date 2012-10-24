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
	const ORDER_FORCECLOSED		=	'order force-closed';
	const ORDER_REVOKED			=	'order revoked';
	const ORDER_REWORK			=	'order in rework';
	const ORDER_REOPENED		=	'order reopened';
	const CORRECTION_UPLOADED 	=	'correction uploaded';
	const CORRECTION_COMMITTED 	= 	'correction committed';
	const CORRECTION_ACCEPTED 	=	'correction accepted';
	const CORRECTION_REJECTED 	= 	'correction rejected';
	const CORRECTION_COMMENTED 	= 	'correction commented';
	
	public function __construct($logType, $comment, Order $order)
	{
		parent::__construct();
		$this->logType = $logType;
		$this->comment = $comment;
		$this->order = $order;
	}
	
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
		return $this->createdAt;
	}
	
	
	/**
	 * @return string 
	 */
	public function getLogType()
	{
		return $this->logType;
	}

	
	/**
	 * @return text 
	 */
	public function getComment()
	{
		return $this->comment;
	}

	
	/**
	 * @return Order
	 */
	public function getOrder()
	{
		return $this->order;
	}

}