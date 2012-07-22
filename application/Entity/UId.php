<?php

namespace Entity;

/**
 * @Entity
 * @Table(name="uid")
 */
class UId
{
	/**
	 * @var string
	 * @Id @Column(type="string")
	 */
	private $id;
	
	
	/**
	 * @var string
	 * @Column(type="string")
	 */
	private $class;
	
	
	public function __construct($class){
		$this->class = $class;
		$this->id = base_convert(crc32(uniqid()), 10, 16);
	}
	
	/**
	 * @return string
	 */
	public function getId(){
		return $this->id;
	}
	
	
	/**
	 * @return string
	 */
	public function getClass(){
		return $this->class;
	}
	
}