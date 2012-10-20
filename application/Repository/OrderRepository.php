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
		$qb = $this->createQueryBuilder("o");
		$qb->innerJoin("o.user", "u")
		->where("o.user_id = '" . $user->getId() . "'")
		->andwhere("$order->getState() != Order::STATE_CLOSED");
			
		return $qb->getQuery()->getResult();
	
	}
}