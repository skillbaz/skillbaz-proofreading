<?php

namespace Repository;

use Entity\Field;

use Doctrine\ORM\EntityRepository;


class ProofreaderRepository 
	extends EntityRepository
{
	//Method to get a list with all Proofreaders who have a certain specialization
	public function findByAbility(Field $field)
	{
		$qb = $this->createQueryBuilder("pr");
		$qb->innerJoin("pr.abilities", "a")
			->where("a.field_id = '" . $field->getId() . "'");
			
		return $qb->getQuery()->getResult();
		
	}
	
		
		
		
		

}