<?php 

class EntityTest extends PHPUnit_Framework_TestCase {
	
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
	
	/**
	 * @var Core\Entity\EntitySerializer
	 * @Inject Core\Entity\EntitySerializer
	 */
	private $entitySerializer;
	
	public function setUp(){
		global $application;
		
		$application->bootstrap();
		
		$kernel = Zend_Registry::get('kernel');
		$kernel->Inject($this);
	}
	
	public function testPersistAndRemoveEntity(){
		$uidRepo = $this->em->getRepository('Entity\UId');
		
		$this->em->getConnection()->beginTransaction();
		
		$user = Helper\UserHelper::Create($this->em);
		$userUid = $uidRepo->find($user->getId());
		
		$this->assertNotNull($userUid);
		$this->assertEquals(get_class($user), $userUid->getClass());
		$this->assertEquals($user->getId(), $userUid->getId());
		
		
		$this->em->remove($user);
		$this->em->flush();
		
		$uidRepo->clear();
		$userUid = $uidRepo->find($user->getId());
		$this->assertNull($userUid);
		
		$this->em->getConnection()->rollback();
	}
	
	
	public function testEntitySerializer(){
		$this->em->getConnection()->beginTransaction();
		
		$user = Helper\UserHelper::Create($this->em);
		$str = $this->entitySerializer->SerializeEntity($user);
		
		$this->assertContains($user->getFirstname(), $str);
		$this->assertContains($user->getSurname(), $str);
		$this->assertContains($user->getEmail(), $str);
		$this->assertContains(date_format($user->getUpdatedAt(), 'd.m.Y  H:i:s'), $str);
		$this->assertContains(date_format($user->getCreatedAt(), 'd.m.Y  H:i:s'), $str);
		
		$user->__toString();
		
		$this->em->getConnection()->rollback();
	}
	
	/*
	public function testBossRole(){
		$this->em->getConnection()->beginTransaction();
		
		$this->em->getConnection()->rollback();
	}
	*/
	
}