<?php

namespace Core\Acl;

use Entity\User;
use Entity\Firm;
use Entity\Member;

class Acl extends \Zend_Acl
{
	const GUEST 					= 'guest';
	const USER						= 'user';
	const MEMBER					= 'member';
	const ADMIN 					= 'admin';
	const BOSS						= 'boss';
	const CUSTOMER					= 'customer';
	const PROOFREADER				= 'proofreader';
	const RESPONSIBLE_PROOFREADER 	= 'responsible_proofreader';
	
	
	/**
	 * @var Doctrine\ORM\EntityManager
	 * @Inject Doctrine\ORM\EntityManager
	 */
	protected $em;
	
	
	/**
	 * @var Core\Acl\ContextStorage
	 * @Inject Core\Acl\ContextStorage
	 */
	protected $contextStorage;
	
	/**
	 * @var array
	 */
	private $rolesCache = array();
	
	
	/**
	 * Setup roles
	 */
	public function __construct()
	{
		 
		// general roles
		$this->addRole(self::GUEST)
			 ->addRole(self::USER, 		self::GUEST)
			 ->addRole(self::CUSTOMER, 	self::USER)
			 ->addRole(self::ADMIN, 	self::USER);
		 
		// Firm related Roles
		$this->addRole(self::MEMBER, 	self::USER)
			 ->addRole(self::BOSS, 		self::MEMBER);
		
		// Proofreader related Roles
		$this->addRole(self::PROOFREADER, 				self::USER)
			 ->addRole(self::RESPONSIBLE_PROOFREADER, 	self::PROOFREADER);
	}
	
	
	public function getRolesInContext()
	{
		$context = $this->contextStorage->getContext();
		$contextKey = (string) $context;
		
		$roles = array_key_exists($contextKey, $this->rolesCache) ?
			$roles = $this->rolesCache[$contextKey] : 
			$this->calculateRolesFromContext($context);
		
		return $roles;
	}
	
	
	private function calculateRolesFromContext(Context $context)
	{
		$user 			= $context->getUser();
		$proofreader 	= $context->getProofreader();
		$order 			= $context->getOrder();
		$firm			= $context->getFirm();
		
		
		$roles = array(self::GUEST);
		
		if($user != null){
			$roles[] = self::USER;
			
			if($user->isAdmin()){
				$roles[] = self::ADMIN;
			}
		}
		
		
		if($proofreader != null){
			$roles[] = self::PROOFREADER;
		}
		
		
		if($order != null && $user != null){
			if($order->getUser() == $user){
				$roles[] = self::CUSTOMER;
			}
		}
		
		
		if($order != null && $proofreader != null){
			if($order->getProofreader() == $proofreader){
				$roles[] = self::RESPONSIBLE_PROOFREADER;
			}
		}
		
		
		if($firm != null && $user != null){
			if($this->isMember($user, $firm)){
				$roles[] = self::MEMBER;
			}
			
			if($this->isBoss($user, $firm)){
				$roles[] = self::BOSS;
			}
		}
		
		
		$this->rolesCache[(string)$context] = $roles;
		
		return $roles;
	}
	
	
	private function isMember(User $user, Firm $firm){
		
		$criteria = array(
			"user" => $user->getId(),
			"firm" => $firm->getId()
		);
		
		$member = $this->em->getRepository("Entity\Member")->findOneBy($criteria);
		
		return $member != null;
		
	}
	
	
	private function isBoss(User $user, Firm $firm){
		$criteria = array(
			"user" => $user->getId(),
			"firm" => $firm->getId(),
			"role"    => Member::BOSS
		);
		
		$member = $this->em->getRepository("Entity\Member")->findOneBy($criteria);
		
		return $member != null;
	}
	
}