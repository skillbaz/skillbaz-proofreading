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
		$qb->where("o.user_id = '" . $user->getId() . "'")
		->andwhere('o.state != ' . Order::STATE_CLOSED);
		
		return $qb->getQuery()->getResult();
	}
	
	//Method to get all orders arranged by their deadline
	public function findAllOrders()
	{
		$qb = $this->createQueryBuilder("o");
		$qb->orderBy("o.deadline", "ASC");
		
		return $qb->getQuery()->getResult();
	}
}

