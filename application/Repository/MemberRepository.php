<?php

namespace Repository;

use Entity\Firm;
use Entity\User;

use Doctrine\ORM\EntityRepository;


class MemberRepository extends EntityRepository
{
	//Finds a specific member instance based on a firm and a user
	public function findMembership(Firm $firm, User $user)
	{
		$qb = $this->createQueryBuilder("m");
		$qb->where("m.firm_id = '" . $firm->getId() . "'")
			->andwhere("m.user_id = '" . $user->getId() . "'");	
		
		return $qb->getQuery()->getResult();
		
	}
}