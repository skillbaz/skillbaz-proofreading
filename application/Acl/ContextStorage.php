<?php

namespace Acl;


use Acl\Context;

class ContextStorage
{
	
	/**
	 * @var Repository\UserRepository
	 * @Inject Repository\UserRepository
	 */
	private $userRepo;
	
	/**
	 * @var Repository\OrderRepository
	 * @Inject Repository\OrderRepository 
	 */
	private $orderRepo;
	
	/**
	 * @var Repository\FirmRepository
	 * @Inject Repository\FirmRepository
	 */
	private $firmRepo;
	
	
	private $orderId = null;
	private $firmId = null;
	
	private $context = null;

	
	public function set($orderId, $firmId)
	{
		$this->context = null;
		
		$this->orderId = $orderId;
		$this->firmId = $firmId;
	}
	
	
	/**
	 * @return Acl\Context
	 */
	public function getContext()
	{
		if(isset($this->context))
		{	return $this->context;	}
		
		$orderId = $this->orderId;
		$firmId  = $this->firmId;
		
		$user    		= $this->getAuthUser();
		$proofreader 	= $user->getProofreader();
		
		$order = isset($orderId) ? $this->orderRepo->find($orderId) : null;
		$firm  = isset($formId)  ? $this->firmRepo->find( $firmId ) : null;
		
		$this->context = new Context($user, $proofreader, $order, $firm);
		return $this->context;
	}
	
	
	/**
	 * @return Entity\User
	 */
	public function getAuthUser()
	{
		$meId = \Zend_Auth::getInstance()->getIdentity();
		$me = isset($meId) ? $this->userRepo->find(  $meId  ) : null;
		return $me;
	}

}