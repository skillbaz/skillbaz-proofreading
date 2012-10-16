<?php
namespace Core\Service;

abstract class ServiceBase
	implements \Zend_Acl_Resource_Interface
{
	/**
	 * @var Doctrine\ORM\EntityManager
	 * @Inject Doctrine\ORM\EntityManager
	 */
	protected $em;
	
	
	/**
	 * @var Core\Acl\Acl
	 * @Inject Core\Acl\Acl
	 */
	protected $acl;
	
	
	/**
	 * @var Core\Acl\ContextProvider
	 * @Inject Core\Acl\ContextProvider
	 */
	protected $contextProvider;
	
	
	abstract public function _setupAcl();
	
	
	public function getResourceId()
	{	
		return get_class($this);	
	}
	
	
	/**
	 * @return Core\Acl\Context
	 */
	public function getContext()
	{
		$this->contextProvider->getContext();
	}
	
	
	protected function remove($entity)
	{
		$this->em->remove($entity);
	}
	
	protected function persist($entity)
	{
		$this->em->persist($entity);
	}
	
	
	
	protected function validationFailed($failed = true, $message = null){
		if($failed){
			ServiceWrapper::validationFailed();
			
			if($message != null){
				ServiceWrapper::addValidationMessage($message);
			}
		}
	}
	
	protected function validationAssert($assert = false, $message = null){
		$this->validationFailed(!$assert, $message);
	}
	
	protected function addValidationMessage($message){
		ServiceWrapper::addValidationMessage($message);
	}
	
	protected function hasFailed(){
		ServiceWrapper::hasFailed();
	}
	
}
	