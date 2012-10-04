<?php

namespace Core\Entity;

class RepoFactory
	implements \PhpDI\Factory\IFactory
{
	private $em;
	private $entityClass;
	
	public function __construct($em, $entityClass){
		$this->em = $em;
		$this->entityClass = $entityClass;
	}
	
	public function create(){
		return $this->em->getRepository($this->entityClass);
	}
	
}