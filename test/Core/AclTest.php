<?php 

class AclTest extends PHPUnit_Framework_TestCase {
	
	
	/**
	 * @var Core\Acl\Acl
	 * @Inject Core\Acl\Acl
	 */
	private $acl;
	
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
	
	
	
	public function testGuestRole(){
		$this->contextStorage->set(null, null);
		
		$context = $this->contextProvider->getContext();
		$this->assertNull($context->getUser());
		$this->assertNull($context->getProofreader());
		$this->assertNull($context->getFirm());
		$this->assertNull($context->getOrder());
		
		$roles = $this->acl->getRolesInContext();
		$this->assertContains(Core\Acl\Acl::GUEST, $roles);
	}
	
	
	public function testUserRole(){
		$this->em->getConnection()->beginTransaction();
		
		$user = Helper\UserHelper::Create($this->em);
		
		\Zend_Auth::getInstance()->getStorage()->write($user->getId());
		$this->contextStorage->set(null, null);
		
		$context = $this->contextProvider->getContext();
		$this->assertEquals($user, $context->getUser());
		
		$roles = $this->acl->getRolesInContext();
		$this->assertContains(Core\Acl\Acl::USER, $roles);
		
		$this->em->getConnection()->rollback();
	}
	
	
	public function testProofreaderRole(){
		$this->em->getConnection()->beginTransaction();
		
		$proofreader = Helper\ProofreaderHelper::Create($this->em);
		
		\Zend_Auth::getInstance()->getStorage()
			->write($proofreader->getUser()->getId());
		$this->contextStorage->set(null, null);
		
		$context = $this->contextProvider->getContext();
		
		$this->assertEquals($proofreader->getUser(), $context->getUser());
		$this->assertEquals($proofreader, $context->getProofreader());
		
		$roles = $this->acl->getRolesInContext();
		$this->assertContains(Core\Acl\Acl::USER, $roles);
		$this->assertContains(Core\Acl\Acl::PROOFREADER, $roles);
		
		$this->em->getConnection()->rollback();
	}
	
	
	public function testCustomerRole(){
		$this->em->getConnection()->beginTransaction();
		
		$user = Helper\UserHelper::Create($this->em);
		$order = Helper\OrderHelper::CreateOpenOrder($user, $this->em);
		
		\Zend_Auth::getInstance()->getStorage()
			->write($user->getId());
		$this->contextStorage->set($order->getId(), null);
		
		$context = $this->contextProvider->getContext();
		
		$this->assertEquals($user, $context->getUser());
		$this->assertEquals($order, $context->getOrder());
		
		$roles = $this->acl->getRolesInContext();
		$this->assertContains(Core\Acl\Acl::USER, $roles);
		$this->assertContains(Core\Acl\Acl::CUSTOMER, $roles);
		
		$this->assertNotContains(Core\Acl\Acl::RESPONSIBLE_PROOFREADER, $roles);
		
		$this->em->getConnection()->rollback();
	}
	
	
	public function testResponsibelProofreaderRole(){
		$this->em->getConnection()->beginTransaction();
		
		$user = Helper\UserHelper::Create($this->em);
		$proofreader = Helper\ProofreaderHelper::Create($this->em);
		$order = Helper\OrderHelper::CreateTakenOrder($user, $proofreader, $this->em);
		
		\Zend_Auth::getInstance()->getStorage()
			->write($proofreader->getUser()->getId());
		$this->contextStorage->set($order->getId(), null);
		
		$context = $this->contextProvider->getContext();
		
		$this->assertEquals($proofreader->getUser(), $context->getUser());
		$this->assertEquals($proofreader, $context->getProofreader());
		$this->assertEquals($order, $context->getOrder());
		
		$roles = $this->acl->getRolesInContext();
		$this->assertContains(Core\Acl\Acl::USER, $roles);
		$this->assertContains(Core\Acl\Acl::PROOFREADER, $roles);
		$this->assertContains(Core\Acl\Acl::RESPONSIBLE_PROOFREADER, $roles);
		
		$this->assertNotContains(Core\Acl\Acl::CUSTOMER, $roles);
		
		$this->em->getConnection()->rollback();
	}
	
	
	public function testMemberRole(){
		$this->em->getConnection()->beginTransaction();
		
		$user = Helper\UserHelper::Create($this->em);
		$firm = Helper\FirmHelper::CreateWithMember($user, $this->em);
		
		\Zend_Auth::getInstance()->getStorage()
			->write($user->getId());
		$this->contextStorage->set(null, $firm->getId());
		
		$context = $this->contextProvider->getContext();
		
		$this->assertEquals($user, $context->getUser());
		$this->assertEquals($firm, $context->getFirm());
		
		$roles = $this->acl->getRolesInContext();
		$this->assertContains(Core\Acl\Acl::USER, $roles);
		$this->assertContains(Core\Acl\Acl::MEMBER, $roles);
		
		$this->assertNotContains(Core\Acl\Acl::BOSS, $roles);
		
		$this->em->getConnection()->rollback();
	}
	
	
	public function testBossRole(){
		$this->em->getConnection()->beginTransaction();
		
		$user = Helper\UserHelper::Create($this->em);
		$firm = Helper\FirmHelper::CreateWithBoss($user, $this->em);
		
		\Zend_Auth::getInstance()->getStorage()
			->write($user->getId());
		$this->contextStorage->set(null, $firm->getId());
		
		$context = $this->contextProvider->getContext();
		
		$this->assertEquals($user, $context->getUser());
		$this->assertEquals($firm, $context->getFirm());
		
		$roles = $this->acl->getRolesInContext();
		$this->assertContains(Core\Acl\Acl::USER, $roles);
		$this->assertContains(Core\Acl\Acl::MEMBER, $roles);
		$this->assertContains(Core\Acl\Acl::BOSS, $roles);		
		
		$this->em->getConnection()->rollback();
	}
	
	
	public function testOne(){
		
		$this->assertContains(Core\Acl\Acl::ADMIN, $this->acl->getRoles());
		
	}
}