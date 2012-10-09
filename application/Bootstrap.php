<?php

use Core\Service\ServiceFactory;

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
		
		
		
		$coreAutoloader = new \Doctrine\Common\ClassLoader('Core', APPLICATION_PATH);
		$autoloader->pushAutoloader(array($coreAutoloader, 'loadClass'), 'Core');
		
		$entityAutoloader = new \Doctrine\Common\ClassLoader('Entity', APPLICATION_PATH);
		$autoloader->pushAutoloader(array($entityAutoloader, 'loadClass'), 'Entity');
		
		$serviceAutoloader = new \Doctrine\Common\ClassLoader('Service', APPLICATION_PATH);
		$autoloader->pushAutoloader(array($serviceAutoloader, 'loadClass'), 'Service');
	
		$repoAutoloader = new \Doctrine\Common\ClassLoader('Repository', APPLICATION_PATH);
		$autoloader->pushAutoloader(array($repoAutoloader, 'loadClass'), 'Repository');
	}
	
	
	/**
	 * Setup Doctrine ORM
	 */
	protected function _initSetupDoctrine()
	{
		/** Setup Doctrine: */
		$opt = $this->getOption('doctrine');
		$container = new Bisna\Doctrine\Container($opt);
		\Zend_Registry::set('doctrineContainer', $container);
		
		/** return DoctrineContainer */
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
		
		$kernel->Bind("Core\Acl\Acl")->ToSelf()->AsSingleton();
		$kernel->Bind("Core\Acl\ContextStorage")->ToSelf()->AsSingleton();
		$kernel->Bind("Core\Acl\ContextProvider")->ToSelf()->AsSingleton();
		$kernel->Bind("Core\Entity\EntitySerializer")->ToSelf()->AsSingleton();
		
		// register services in kernel:
		$this->registerService("Service\UserService");
		$this->registerService("Service\LoginService");
	}
	
	
	/**
	 * Registers a Service for public (but protected with ACL)
	 * and for in-Service calls.
	 * 
	 * The serviceWrapper is registered under Acl\Service\AnyService
	 * The raw service is registered under Service\AnyService
	 * 
	 * @param string $serviceClass
	 */
	private function registerService($serviceClass)
	{
		$kernel = Zend_Registry::get("kernel");
		
		// registers the service for in-service calls:
		$kernel->Bind($serviceClass)->ToSelf()->AsSingleton();
		
		// registers the service for protected calls:
		$kernel->Bind("Acl\\" . $serviceClass)->ToFactory(new ServiceFactory($kernel, $serviceClass));
	}
	
	public function _initUIdManagerSetup()
	{
		$kernel = Zend_Registry::get("kernel");
		
		/** Setup UIdManager: */
		$UIdManager = $kernel->Get('Core\Entity\UIdManager');
		$em = $kernel->Get('Doctrine\ORM\EntityManager');
		$em->getEventManager()->addEventListener(array('prePersist', 'preRemove'), $UIdManager);
	}
	
	
	public function _initRoute()
	{
		$kernel = Zend_Registry::get('kernel');
		
		$orderRoute = new Core\Router\UIdRoute(
			'order/:order/:controller/:action',
			array('controller' => 'index', 'action' => 'index'),
			array('order' => new Core\Router\UIdCheck('Entity\Order'))
		);
		
		$firmRoute = new Core\Router\UIdRoute(
			'firm/:firm/:controller/:action',
			array('controller' => 'index', 'action' => 'index'),
			array('firm' => new Core\Router\UIdCheck('Entity\Firm'))
		);
		
		
		$kernel->Inject($orderRoute);
		$kernel->Inject($firmRoute);
		
		
		Zend_Controller_Front::getInstance()->getRouter()->addRoute('order', $orderRoute);
		Zend_Controller_Front::getInstance()->getRouter()->addRoute('firm', $firmRoute);
		
	}
}

