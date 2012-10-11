<?php

namespace Repository;

use Doctrine\ORM\EntityRepository;
use Entity\Order;


class CorrectionRepository extends EntityRepository
{
	
	/**
	 * Get the most recent correction entity from an order
	 * @param Order $order
	 */
	public function getRecentCorrection(Order $order)
	{
		$qb = $this->createQueryBuilder("c");
		$qb->innerJoin("c.order", "o")
			->where("o.order_id = '" . $order->getId() . "'")
			->orderBy("c.version", "DESC")->setMaxResults(1);
		
		return $qb->getQuery()->getResult();
	}
	
}