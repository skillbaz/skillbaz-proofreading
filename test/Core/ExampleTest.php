<?php 

class ExampleTest extends PHPUnit_Framework_TestCase {
	
	
	/**
	 * @var Core\Acl\Acl
	 * @Inject Core\Acl\Acl
	 */
	private $acl;
	
	public function setUp(){
		global $application;
		
		$application->bootstrap();
		
		$kernel = Zend_Registry::get('kernel');
		$kernel->Inject($this);
	}
	
	public function testOne(){
		
		$this->assertContains(Core\Acl\Acl::ADMIN, $this->acl->getRoles());
		
	}
}