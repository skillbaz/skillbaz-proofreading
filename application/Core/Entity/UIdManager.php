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
	
	/**
	 * @var Doctrine\ORM\Mapping\ClassMetadata
	 */
	private $baseEntityMetadata = null;
	
	
	public function prePersist(LifecycleEventArgs $eventArgs)
	{
		$entity = $eventArgs->getEntity();
		
		if($entity instanceof \Core\Entity\BaseEntity)
		{
			$uid = $this->getUid(get_class($entity));
			$this->setId($entity, $uid->getId());
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
	
	
	
	private function setId(BaseEntity $entity, $id)
	{
		if($this->baseEntityMetadata == null){
			$this->baseEntityMetadata = $this->em->getClassMetadata('Core\Entity\BaseEntity');
		}
	
		$this->baseEntityMetadata->setFieldValue($entity, 'id', $id);
	}
		
	public function getUid($class)
	{
		$uid = new UId($class);
		$this->em->persist($uid);
		
		return $uid;
	}
	
}
