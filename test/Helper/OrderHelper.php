<?php

namespace Helper;


use Entity\User;
use Entity\Proofreader;
use Entity\Order;
use Doctrine\ORM\EntityManager;

class OrderHelper{
	
	public static function CreateOpenOrder(User $user, EntityManager $em = null){
		
		$document = DocumentHelper::Create($em);
		$pricing = PricingHelper::Create($em);
		$field = FieldHelper::Create($em);
		
		$order = new Order($user, $user->getAddress(), $document, $pricing, $field);
		$order->setState(Order::STATE_OPEN);
		$order->setDescription('desc');
		$order->setPublicComment('publicComment');
		$order->setInternalComment('internalComment');
		
		if($em != null){
			$em->persist($order);
			$em->flush($order);
			
			$em->refresh($document);
			$em->refresh($pricing);
			$em->refresh($field);
			$em->refresh($user);
		}
		
		return $order;
	}
	
	
	public static function CreateTakenOrder(User $user, Proofreader $proofreader, EntityManager $em = null){
	
		$document = DocumentHelper::Create($em);
		$pricing = PricingHelper::Create($em);
		$field = FieldHelper::Create($em);
		
		$order = new Order($user, $user->getAddress(), $document, $pricing, $field);
		$order->setDescription('desc');
		$order->setPublicComment('publicComment');
		$order->setInternalComment('internalComment');
	
		$order->setProofreader($proofreader);
		$order->setState(Order::STATE_WORKING);
				
		if($em != null){
			$em->persist($order);
			$em->flush($order);
				
			$em->refresh($document);
			$em->refresh($pricing);
			$em->refresh($field);
			
			$em->refresh($user);
			$em->refresh($proofreader);
		}
	
		return $order;
	}
	
}
