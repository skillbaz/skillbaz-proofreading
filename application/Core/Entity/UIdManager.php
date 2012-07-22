<?php

namespace Core\Entity;

use Entity\UId;
use Doctrine\ORM\Event\LifecycleEventArgs;


class UIdManager
{
	const DELETE_UID = 'DELETE Entity\Uid u WHERE u.id = :id';
	
	
	/**
	 * @var Doctrine\ORM\EntityManager
	 * @Inject Doctrine\ORM\EntityManager
	 */
	private $em;
	
	
	public function prePersist(LifecycleEventArgs $eventArgs)
	{
		$entity = $eventArgs->getEntity();
		
		if($entity instanceof \Core\Entity\BaseEntity)
		{
			$class = get_class($entity);
			$uid = $this->getUid($class);
			
			EntityIdSetter::SetId($entity, $uid->getId());
		}
	}
	
	
	public function preRemove(LifecycleEventArgs $eventArgs)
	{
		$entity = $eventArgs->getEntity();
	
		if($entity instanceof \Core\Entity\BaseEntity)
		{
			$q = $this->em->createQuery(self::DELETE_UID);
			$q->execute(array('id' => $entity->getId()));
		}
	}
	
	
	public function getUid($class)
	{
		$uid = new UId($class);
		$this->em->persist($uid);
		$this->em->flush($uid);
	
		return $uid;
	}
	
}


class EntityIdSetter
	extends \Core\Entity\BaseEntity
{
	public static function SetId(\Core\Entity\BaseEntity $entity, $id)
	{
		$entity->id = $id;
	}
}	