<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	
	/**
	 * Register Autoloaders for different Namespaces
	 * in APPLICATION_PATH and library
	 */
	public function _initAutoloaderNamespaces()
	{
		require_once APPLICATION_PATH . '/../library/Doctrine/Common/ClassLoader.php';
	
		$autoloader = \Zend_Loader_Autoloader::getInstance();
		
		$fmmAutoloader = new \Doctrine\Common\ClassLoader('Bisna');
		$autoloader->pushAutoloader(array($fmmAutoloader, 'loadClass'), 'Bisna');
		
		$PhpdiAutoloader = new \Doctrine\Common\ClassLoader('PhpDI');
		$autoloader->pushAutoloader(array($PhpdiAutoloader, 'loadClass'), 'PhpDI');
		
		
		$aclAutoloader = new \Doctrine\Common\ClassLoader('Acl', APPLICATION_PATH);
		$autoloader->pushAutoloader(array($aclAutoloader, 'loadClass'), 'Acl');
		
		$entityAutoloader = new \Doctrine\Common\ClassLoader('Entity', APPLICATION_PATH);
		$autoloader->pushAutoloader(array($entityAutoloader, 'loadClass'), 'Entity');
		
		$serviceAutoloader = new \Doctrine\Common\ClassLoader('Service', APPLICATION_PATH);
		$autoloader->pushAutoloader(array($serviceAutoloader, 'loadClass'), 'Service');
	}
	
	
	/**
	 * Setup Doctrine ORM
	 */
	protected function _initSetupDoctrine()
	{
		$opt = $this->getOption('doctrine');
		$container = new Bisna\Doctrine\Container($opt);
		\Zend_Registry::set('doctrineContainer', $container);
		return $container;
	}
	
	
	/**
	 * Create a Dependency Injection Kernel and bind Dependencies 
	 */
	public function _initInjectionKernel()
	{
		$kernel = new \PhpDI\Kernel();
		Zend_Registry::set("kernel", $kernel);
		
		$kernel->Bind("PhpDI\Kernel")->ToConstant($kernel);
		$kernel->Bind("Doctrine\ORM\EntityManager")
				->ToConstant(Zend_Registry::get('doctrineContainer')->getEntityManager());
		
		$kernel->Bind("Acl\Acl")->ToSelf()->AsSingleton();
		$kernel->Bind("Acl\ContextStorage")->ToSelf()->AsSingleton();
		$kernel->Bind("Acl\ContextProvider")->ToSelf()->AsSingleton();
		
		
		//todo: register Services
	}
}

