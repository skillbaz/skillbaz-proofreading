<?php

namespace Repository;

use Doctrine\ORM\EntityRepository;
use Entity\AccountsReceivable;


class AccountsReceivableRepository extends EntityRepository
{
	
	/**
	 * Get all accounts receivable with a certain status
	 * @param String $state
	 */
	public function getAccountsReceivable($state)
	{
		$qb = $this->createQueryBuilder("ar");
		$qb->where('ar.state = ' . $state);
		
		return $qb->getQuery()->getResult();
	}
	
}