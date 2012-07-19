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
	
	
	/**
	 * @var Acl\Acl
	 * @Inject Acl\Acl
	 */
	protected $acl;
	
	
	/**
	 * @var Acl\ContextProvider
	 * @Inject Acl\ContextProvider
	 */
	protected $contextProvider;
	
	
	abstract public function _setupAcl();
	
	
	public function getResourceId()
	{	
		return get_class($this);	
	}
	
	
	/**
	 * @return Acl\Context
	 */
	public function getContext()
	{
		$this->contextProvider->getContext();
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
	