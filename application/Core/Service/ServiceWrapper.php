<?php

namespace Core\Service;

use Bisna\Application\Resource\Doctrine;

class ServiceWrapper
	implements \Zend_Acl_Resource_Interface
{
	/**
	 * @var Doctrine\ORM\EntityManager
	 * @Inject Doctrine\ORM\EntityManager
	 */
	private $em;
	
	/**
	 * @var PhpDI\IKernel
	 * @Inject PhpDI\IKernel
	 */
	private $kernel;
	
	/**
	 * @var Acl\Acl
	 * @Inject Acl\Acl
	 */
	private $acl;
	
	/**
	 * The protected Resource
	 * @var Zend_Acl_Resource_Interface
	 */
	private $service = null;
	
	/**
	 * @var Exception
	 */
	private $exception = null;
	
	/**
	 * @var ValidationException
	 */
	public static $validationException = null;
	
	
	public function __construct(\Zend_Acl_Resource_Interface $service)
	{
		$this->service = $service;
	}
	
	public function postInject()
	{
		$this->acl->addResource($this->service);
		$this->kernel->Inject($this->service);
	
		unset($this->kernel);
	}
	
	public function getResourceId()
	{
		return $this->service->getResourceId();
	}
	
	
	public static function validationFailed(){
		if(self::$validationException == null){
			self::$validationException = new ValidationException();
		}
	}
	
	public static function addValidationMessage($message){
		self::validationFailed();
		self::$validationException->addMessage($message);
	}
	
	public static function hasFailed(){
		return self::$validationException != null;
	}
	
	public function __call($method, $args)
	{
		if(!method_exists($this->service, $method)){
			throw new \Exception("Method $method does not exist.");
		}
		
		$this->service->_setupAcl();
		
		if( ! $this->isAllowed($method) )
			throw new \Exception("No Access on " . $this->service->getResourceId() . "::" . $method);
			
		$this->start();
		
		try{
			$r = call_user_func_array(array($this->service, $method), $args);
		}
		catch(\Exception $e){
			$this->exception = $e;
		}
		
		$this->end();
		
		return $r;
	}
	
	private function isAllowed($privilege = NULL)
	{
		$roles = $this->acl->getRolesInContext();
	
		foreach ($roles as $role)
		{
			if($this->acl->isAllowed($role, $this->service, $privilege))
			{
				return true;
			}
		}
	
		return false;
	}
	
	private function start()
	{
		$this->exception = null;
		self::$validationException = null;
		
		$uow = $this->em->getUnitOfWork();
		$uow->computeChangeSets();
		
		$upd = $uow->getScheduledEntityUpdates();
		$ins = $uow->getScheduledEntityInsertions();
		$del = $uow->getScheduledEntityDeletions();
		$colupd = $uow->getScheduledCollectionUpdates();
		$coldel = $uow->getScheduledCollectionDeletions();
		
		if( !empty($upd) || !empty($ins)|| !empty($del)|| !empty($colupd)|| !empty($coldel) )
			throw new \Exception("You tried to edit an entity outside the service layer.");
		
		$this->em->getConnection()->beginTransaction();
	}
	
	private function end()
	{
		if($this->exception != null || self::$validationException != null){
			$this->em->getConnection()->rollback();
			$this->em->clear();
			
			throw $this->exception ?: self::$validationException;
		}
		
		try
		{
			$this->em->flush();
			$this->em->getConnection()->commit();
		}
		catch (\Exception $e)
		{
			$this->em->getConnection()->rollback();
			$this->em->close();
		
			throw $e;
		}
	}
}