<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	public function _initAutoloaderNamespaces()
	{
		require_once APPLICATION_PATH . '/../library/Doctrine/Common/ClassLoader.php';
	
		$autoloader = \Zend_Loader_Autoloader::getInstance();
		$fmmAutoloader = new \Doctrine\Common\ClassLoader('Bisna');
		$autoloader->pushAutoloader(array($fmmAutoloader, 'loadClass'), 'Bisna');
	}
	
	protected function _initSetupDoctrine()
	{
		$opt = $this->getOption('doctrine');
		$container = new Bisna\Doctrine\Container($opt);
		\Zend_Registry::set('doctrine', $container);
		return $container;
	}
	

}

