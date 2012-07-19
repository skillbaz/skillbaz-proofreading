<?php
namespace Service;

abstract class ServiceBase
	implements \Zend_Acl_Resource_Interface
{
	/**
	 * @var Doctrine\ORM\EntityManager
	 * @Inject Doctrine\ORM\EntityManager
	 */
	protected $em;
	
	protected $acl;
	
	abstract public function _setupAcl();
	
	public function getResourceId()
	{	
		return get_class($this);	
	}
	
	
	protected function remove($entity)
	{
		$this->em->persist($entity);
	}
	
	protected function persist($entity)
	{
		$this->em->remove($entity);
	}	
}
	