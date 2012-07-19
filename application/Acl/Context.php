<?php

namespace Acl;

use Entity\User;
use Entity\Proofreader;
use Entity\Order;
use Entity\Firm;

class Context
{
	
	/**
	 * @var Entity\User
	 */
	private $user;
	
	
	/**
	 * @var Entity\Proofreader
	 */
	private $proofreader;
	
	
	/**
	 * @var Entity\Firm
	 */
	private $firm;
	
	
	/**
	 * @var Enitity\Order
	 */
	private $order;
	
	
	
	public function __construct(User $user = null, Proofreader $proofreader = null, Order $order= null, Firm $firm = null)
	{
		$this->user = $user;
		$this->proofreader = $proofreader;
		$this->order = $order;
		$this->firm = $firm;
	}
	
	
	/**
	 * @return Entity\User
	 */
	public function getUser()
	{
		return $this->user;
	}
	
	
	/**
	 * @return Entity\Proofreader
	 */
	public function getProofreader()
	{
		return $this->proofreader;
	}
	
	
	/**
	 * @return Entity\Order
	 */
	public function getOrder()
	{
		return $this->order;
	}
	
	
	/**
	 * @return Entity\Firm
	 */
	public function getFirm()
	{
		return $this->firm;
	}
	
	
	public function __toString()
	{
		$ids = array(
			is_null($this->user) 		? 'null' : $this->user->getId(),
			is_null($this->proofreader) ? 'null' : $this->proofreader->getId(),
			is_null($this->order) 		? 'null' : $this->order->getId(),
			is_null($this->firm) 		? 'null' : $this->firm->getId()
		);
		
		return implode("::", $ids);
	}
	
}
