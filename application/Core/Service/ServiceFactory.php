<?php

namespace Core\Service;

class ServiceFactory
	implements \PhpDI\Factory\IFactory
{
	
	/**
	 * @var string
	 */
	private $service;
	
	
	/**
	 * @var PhpDI\IKernel
	 */
	private $kernel;
	
	
	public function __construct(\PhpDI\IKernel $kernel, $service)
	{
		$this->kernel = $kernel;
		$this->service = $service;
	}
	
	public function create()
	{
		$service = $this->kernel->Get($this->service);
		$serviceWrapper = new ServiceWrapper($service);
		
		return $serviceWrapper;
	}
	
}