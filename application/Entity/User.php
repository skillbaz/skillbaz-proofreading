<?php

namespace Entity;

use Core\Entity\BaseEntity;


/**
 * @Entity
 * @Table(name="users")
 *
 */
class User extends BaseEntity
{
	
	/**
	 * e-mail address, unique
	 * @Column(type="string", length=64, nullable=true, unique=true )
	 */
	private $email;
	
	
	/**
	 * Indicates whether the User is active
	 * @Column(type="boolean")
	 */
	private $active;
	
	
	/**
	 * @var Entity\Login
	 * @OneToOne(targetEntity="Login", mappedBy="user")
	 */
	private $login;
	
	
	
	public function setEmail($email){
		$this->email = $email;
	}
	
	public function getEmail(){
		return $this->email;
	}
	
	
	public function setActive($active = true){
		$this->active = $active;
	}
	
	public function getActive(){
		return $this->active;
	}
	
	/**
	 * @return Login
	 */
	public function getLogin()
	{
		return $this->login;
	}
}