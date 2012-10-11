<?php

namespace Repository;

use Doctrine\ORM\EntityRepository;


class PricingRepository 
	extends EntityRepository
{
	//Method to find the possible Pricing-Schemes for a certain document
	public function findAllowedPricings($wordCount)
	{
		$qb = $this->createQueryBuilder("p");
		$qb->where("p.maxWords >= $wordCount")
			->andWhere("p.active = true")
			->orderBy("p.wordPrice");
		
		return $qb->getQuery()->getResult();
	}
	
}