<?php

namespace Repository;

use Doctrine\ORM\EntityRepository;
use Entity\Order;
use Entity\User;

class OrderRepository extends EntityRepository
{
	//Method to get all orders of a user which are not yet closed
	public function findOpenOrders(User $user)
	{
		$qb = $this->creatQueryBuilder("o");
		$qb->where("o.user_id = '" . $user->getId() . "'")
		->andwhere('o.state != ' . Order::STATE_CLOSED);
		
		return $qb->getQuery()->getResult();
	}

	public function findOpenOrdersByAddress(Address $address)
	{
		$qb = $this->createQueryBuilder("o");
		$qb->where("o.address = '" . $address . "'")
			->andwhere('o.state != ' . Order::STATE_CLOSED);
		
		return $qb->getQuery()->getResult();
	}
}
