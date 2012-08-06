<?php

namespace Entity;

use Doctrine\DBAL\Types\StringType;

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
	 * @var DateTime
	 * @ORM\Column(type="date", nullable=true)
	 */
	private $validity;
	
	/**
	 * @var Entity\User
	 * @ORM\OneToOne(targetEntity="User", mappedBy="legi")
	 */
	private $user;
	
	
	
	/**
	 * @return string
	 */
	public function getLegiNumber(){
		return $this->legiNumber;
	}	
	public function setLegiNumber($legiNumber){
		$this->legiNumber = $legiNumber;
	}
	
	
	/**
	 * @return string
	 */
	public function getUniversity(){
		return $this->university;
	}	
	public function setUniversity($university){
		$this->university = $university;
	}
	

	/**
	 * @return DateTime
	 */
	public function getValidity(){
		return $this->validity;
	}	
	public function setValidity($validity){
		$this->validity = $validity;
	}
	
	public function isValid(){
		return new \DateTime('now') < $this->validity;
	}
	
	/**
	 * @return User
	 */
	public function getUser(){
		return $this->user;
	}
	
}