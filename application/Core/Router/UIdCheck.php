<?php

namespace Core\Router;

class UIdCheck
{
	private $class;
	
	public function __construct($class = null){
		$this->class = $class;
	}
	
	public function getClass(){
		return $this->class;
	}
	
	public function __toString(){
		return "UIdCheck(" . $this->class . ")";
	}
}