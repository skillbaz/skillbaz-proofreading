<?php

namespace Entity;

use Core\Entity\BaseEntity;


/**
 * @Entity
 * @Table(name="logins")
 */
class Login extends BaseEntity
{
	
	/**
	 * @Column(type="string", length=64)
	 * @var string
	 */
	private $password;
	
	
	/**
	 * @Column(type="string", length=64)
	 * @var string
	 */
	private $salt;
	
	
	/**
	 * @Column(type="string", length=64, nullable=true)
	 * @var string
	 */
	private $pwResetKey;
	
	
	/**
	 * @var User
	 * @OneToOne(targetEntity="User", mappedBy="login")
	 * @JoinColumn(name="user_id", referencedColumnName="id")
	 */
	public $user;
	
	
	
	
	/**
	 * Set the User of this Login Entity
	 */
	public function setUser(User $user)
	{
		$this->user = $user;
	}
	
	
	/**
	 * Returns the User of this Login Entity
	 *
	 * @return User
	 */
	public function getUser()
	{
		return $this->user;
	}
	
	
	/**
	 * Create a new PW Reset Key
	 */
	public function createPwResetKey()
	{
		$this->pwResetKey = md5(unique(microtime(true)));
		return $this->pwResetKey; 
	}
	
	
	/**
	 * Clears the pwResetKey Field.
	 */
	public function clearPwResetKey()
	{
		$this->pwResetKey = null;
	}
	
	
	/**
	 * Sets a new Password. It creates a new salt
	 * ans stores the salten password
	 * @param string $password
	 */
	public function setNewPassword($password)
	{
		$this->salt = hash('sha256', uniqid(microtime(true), true));
		
		$password .= $this->salt;
		$this->password = hash('sha256', $password);
	}


	/**
	 * Checks the given Password
	 * Returns true, if the given password matches for this Login
	 *
	 * @param string $password
	 *
	 * @return bool
	 */
	public function checkPassword($password)
	{
		$password = hash('sha256', $password . $this->salt);
		
		return ($password == $this->password);
	}
}