<?php 

class AuthTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @var Core\Acl\ContextProvider
	 * @Inject Core\Acl\ContextProvider
	 */
	private $contextProvider;
	
	/**
	 * @var Doctrine\ORM\EntityManager
	 * @Inject Doctrine\ORM\EntityManager
	 */
	private $em;
	
	
	public function setUp(){
		global $application;
		
		$application->bootstrap();
		
		$kernel = Zend_Registry::get('kernel');
		$kernel->Inject($this);
	}
	
	
	public function testUnknownUser(){
		$ada = new Core\Auth\Adapter(null, 'asdf');
		$result = Zend_Auth::getInstance()->authenticate($ada);
		
		$this->assertFalse($result->isValid());
		$this->assertContains(Core\Auth\Adapter::NOT_FOUND_MESSAGE, $result->getMessages());
		
		$context = $this->contextProvider->getContext();
		$this->assertNull($context->getUser());
	}
	
	public function testWrongCredentials(){
		$this->em->getConnection()->beginTransaction();
		
		$login = Helper\LoginHelper::Create($this->em);
		
		$ada = new Core\Auth\Adapter($login->getUser(), 'wrong password');
		$result = Zend_Auth::getInstance()->authenticate($ada);
		
		$this->assertFalse($result->isValid());
		$this->assertContains(Core\Auth\Adapter::CREDINTIALS_MESSAGE, $result->getMessages());
		
		$context = $this->contextProvider->getContext();
		$this->assertNull($context->getUser());
		
		$this->em->getConnection()->rollback();
	}
	
	public function testNotActivated(){
		$this->em->getConnection()->beginTransaction();
		
		$login = Helper\LoginHelper::Create($this->em);
		$login->getUser()->setActive(false);
		$this->em->flush($login->getUser());
		
		$ada = new Core\Auth\Adapter($login->getUser(), '1234');
		$result = Zend_Auth::getInstance()->authenticate($ada);
		
		$this->assertFalse($result->isValid());
		$this->assertContains(Core\Auth\Adapter::NOT_ACTIVATED_MESSAGE, $result->getMessages());
		
		$context = $this->contextProvider->getContext();
		$this->assertNull($context->getUser());
		
		$this->em->getConnection()->rollback();
	}
	
	public function testCorrectLogin(){
		$this->em->getConnection()->beginTransaction();
	
		$login = Helper\LoginHelper::Create($this->em);
	
		$ada = new Core\Auth\Adapter($login->getUser(), '1234');
		$result = Zend_Auth::getInstance()->authenticate($ada);
	
		$this->assertTrue($result->isValid());
		$this->assertEmpty($result->getMessages());
	
		$context = $this->contextProvider->getContext();
		$this->assertEquals($login->getUser(), $context->getUser());
	
		$this->em->getConnection()->rollback();
	}
	
	
	
	/*
	public function testBossRole(){
		$this->em->getConnection()->beginTransaction();
		
		$this->em->getConnection()->rollback();
	}
	*/
	
}