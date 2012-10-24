<?php

namespace Repository;

use Doctrine\ORM\EntityRepository;
use Entity\Order;

class OrderRepository extends EntityRepository
{
	public function findOpenOrdersByAddress(Address $address)
	{
		$qb = $this->createQueryBuilder("o");
		$qb->where("o.address = '" . $address . "'")
			->andwhere('o.state != ' . Order::STATE_CLOSED);
		
		return $qb->getQuery()->getResult();
	}
}