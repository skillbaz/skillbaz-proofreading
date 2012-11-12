<?php

namespace Repository;

use Doctrine\ORM\EntityRepository;
use Entity\AccountsReceivable;
use Entity\Order;


class AccountsReceivableRepository extends EntityRepository
{
	
	/**
	 * Get all accounts receivable with a certain status
	 * @param String $state
	 */
	public function findByState($state)
	{
		$qb = $this->createQueryBuilder("ar");
		$qb->where('ar.state = ' . $state);
		
		return $qb->getQuery()->getResult();
	}
	
	/**
	 * Get a certain account receivable from an order
	 * @param Order $order
	 */
	public function findByOrder(Order $order)
	{
		$qb = $this->createQueryBuilder("ar");
		$qb->where("ar.order = '" . $order->getId() . "'");
		
		return $qb->getQuery()->getResult();
	}
	
}