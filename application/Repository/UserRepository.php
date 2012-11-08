<?php

namespace Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
	
	//Method to get all users
	public function getAllUsers()
	{
		$qb = $this->createQueryBuilder("u");
		$qb->where("u.active = true");
	
		return $qb->getQuery()->getResult();
	}
	
}
