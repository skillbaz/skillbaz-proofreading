<?php

namespace Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
	
	//Method to get all users
	public function getAllUsers()
	{
		$qb = $this->createQueryBuilder("pr");
		$qb-> where('u.proofreader = ' . null)
			->orderBy("u.active");
	
		return $qb->getQuery->getResult();
	}
	
}
