<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	public function _initAutoloaderNamespaces()
	{
		require_once APPLICATION_PATH . '/../library/Doctrine/Common/ClassLoader.php';
	
		$autoloader = \Zend_Loader_Autoloader::getInstance();
		
		$fmmAutoloader = new \Doctrine\Common\ClassLoader('Bisna');
		$autoloader->pushAutoloader(array($fmmAutoloader, 'loadClass'), 'Bisna');
		
		$PhpdiAutoloader = new \Doctrine\Common\ClassLoader('PhpDI');
		$autoloader->pushAutoloader(array($PhpdiAutoloader, 'loadClass'), 'PhpDI');
		
		$serviceAutoloader = new \Doctrine\Common\ClassLoader('Service', APPLICATION_PATH);
		$autoloader->pushAutoloader(array($serviceAutoloader, 'loadClass'), 'Service');
		
		$entityAutoloader = new \Doctrine\Common\ClassLoader('Entity', APPLICATION_PATH);
		$autoloader->pushAutoloader(array($entityAutoloader, 'loadClass'), 'Entity');
	}
	
	protected function _initSetupDoctrine()
	{
		$opt = $this->getOption('doctrine');
		$container = new Bisna\Doctrine\Container($opt);
		\Zend_Registry::set('doctrineContainer', $container);
		return $container;
	}
	
	public function _initInjectionKernel()
	{
		$kernel = new \PhpDI\Kernel();
		Zend_Registry::set("kernel", $kernel);
		
		$kernel->Bind("PhpDI\Kernel")->ToConstant($kernel);
		$kernel->Bind("Doctrine\ORM\EntityManager")
				->ToConstant(Zend_Registry::get('doctrineContainer')->getEntityManager());
		//todo: register Services
	}
}

