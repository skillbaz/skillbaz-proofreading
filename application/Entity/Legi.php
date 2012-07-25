<?php

namespace Entity;

use Core\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="legis")
 */
class Legi extends BaseEntity
{
	/**
	 * @ORM\Column(type="string", length=32, nullable=false)
	 */
	private $legiNumber;
	
	/**
	 * @ORM\Column(type="string", length=64, nullable=true)
	 */
	private $university;
	
	/**
	 * @ORM\Column(type="date", nullable=true)
	 */
	private $validity;
	
	/**
	 * @var Entity\User
	 * @ORM\OneToOne(targetEntity="User", mappedBy="legi")
	 */
	private $user;
	
	
	
	public function setLegiNumber($legiNumber){
		$this->legiNumber = $legiNumber;
	}
	
	public function getLegiNumber(){
		return $this->legiNumber;
	}
	
	public function setUniversity($university){
		$this->university = $university;
	}
	
	public function getUniversity(){
		return $this->university;
	}
	
	public function setValidity($validity){
		$this->validity = $validityty;
	}
	
	public function getValidity(){
		return $this->validity;
	}
	
	/**
	 * @return User
	 */
	public function getUser(){
		return $this->user;
	}
	
}