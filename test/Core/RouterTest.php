<?php 

class RouterTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @var Doctrine\ORM\EntityManager
	 * @Inject Doctrine\ORM\EntityManager
	 */
	private $em;
	
	
	/**
	 * @var Core\Acl\ContextProvider
	 * @Inject Core\Acl\ContextProvider
	 */
	private $contextProvider;
	
	
	public function setUp(){
		global $application;
		
		$application->bootstrap();
		
		$kernel = Zend_Registry::get('kernel');
		$kernel->Inject($this);
	}
	
	
	public function testFirmRouteMatch(){
		
		$firmRoute = Zend_Controller_Front::getInstance()->getRouter()->getRoute('firm');
		
		
		$this->em->getConnection()->beginTransaction();
		
		$user = Helper\UserHelper::Create($this->em);
		$firm = Helper\FirmHelper::CreateWithMember($user, $this->em);
		
		$res = $firmRoute->match('/firm/' . $firm->getId() . '/');
		
		$this->assertNotEquals(false, $res);
		$this->assertEquals($firm, $res['firm']);
		
		$this->em->getConnection()->rollback();
	}
	
	
	public function testFirmRouteAssemble(){
		
		$firmRoute = Zend_Controller_Front::getInstance()->getRouter()->getRoute('firm');
				
		
		$this->em->getConnection()->beginTransaction();
		
		$user = Helper\UserHelper::Create($this->em);
		$firm = Helper\FirmHelper::CreateWithMember($user, $this->em);
		
		$url = $firmRoute->assemble(array('firm' => $firm));
		$this->assertEquals('firm/' . $firm->getId(), $url);
		
		$this->em->getConnection()->rollback();
	}
	
	
	
	public function testOrderRouteMatch(){
		
		$firmRoute = Zend_Controller_Front::getInstance()->getRouter()->getRoute('order');
				
		
		$this->em->getConnection()->beginTransaction();
		
		$user = Helper\UserHelper::Create($this->em);
		$order = Helper\OrderHelper::CreateOpenOrder($user, $this->em);
		
		$res = $firmRoute->match('/order/' . $order->getId() . '/');
		
		$this->assertNotEquals(false, $res);
		$this->assertEquals($order, $res['order']);
		
		$this->em->getConnection()->rollback();
	}
	
	
	public function testOrderRouteAssemble(){
		
		$firmRoute = Zend_Controller_Front::getInstance()->getRouter()->getRoute('order');
						
		
		$this->em->getConnection()->beginTransaction();
		
		$user = Helper\UserHelper::Create($this->em);
		$order = Helper\OrderHelper::CreateOpenOrder($user, $this->em);
		
		$url = $firmRoute->assemble(array('order' => $order));
		$this->assertEquals('order/' . $order->getId(), $url);
		
		$this->em->getConnection()->rollback();
	}
	
	
}