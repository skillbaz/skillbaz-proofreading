<?php 

class LoginServiceTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @var Doctrine\ORM\EntityManager
	 * @Inject Doctrine\ORM\EntityManager
	 */
	private $em;
	
	/**
	 * @var Core\Acl\ContextStorage
	 * @Inject Core\Acl\ContextStorage
	 */
	private $contextStorage;
	
	/**
	 * @var Core\Acl\ContextProvider
	 * @Inject Core\Acl\ContextProvider
	 */
	private $contextProvider;
	
	/**
	 * @var Service\LoginService
	 * @Inject Acl\Service\LoginService
	 */
	private $loginService;
	
	public function setUp(){
		global $application;
		
		$application->bootstrap();
		
		$kernel = Zend_Registry::get('kernel');
		$kernel->Inject($this);
		
		\Zend_Auth::getInstance()->clearIdentity();
	}
	
	
	public function testLoginLogout(){
		$this->em->getConnection()->beginTransaction();
		
		$login = Helper\LoginHelper::Create($this->em);
		$email = $login->getUser()->getEmail();
		$result = $this->loginService->login($email, '1234');
		
		$this->contextStorage->clear();
		$context = $this->contextProvider->getContext();
		$this->assertEquals($login->getUser(), $context->getUser());
		
		
		$this->loginService->logout();
		$this->contextStorage->clear();
		$context = $this->contextProvider->getContext();
		$this->assertNull($context->getUser());
		
		
		$this->em->getConnection()->rollback();
	}
	
	
	public function testPasswordLost(){
		$loginClassMeta = $this->em->getClassMetadata('Entity\Login');
		
		$this->em->getConnection()->beginTransaction();
		
		$login = Helper\LoginHelper::Create($this->em);
		$this->loginService->passwordLost($login->getUser());
		
		$resetKey = $loginClassMeta->getFieldValue($login, 'pwResetKey');
		$this->assertNotNull($resetKey);
		
		
		$this->loginService->resetPassword($resetKey, '2345');
		
		$resetKey = $loginClassMeta->getFieldValue($login, 'pwResetKey');
		$this->assertNull($resetKey);
		$this->assertTrue($login->checkPassword('2345'));
		
		$this->em->getConnection()->rollback();
	}
	
}