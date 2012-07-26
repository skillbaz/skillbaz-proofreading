<?php

namespace Entity;

use Core\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="members")
 */
class Member extends BaseEntity
{
	/**
	 * @ORM\Column(type="string", length=32, nullable=false)
	 */
	private $role;

	/**
	 * @var Entity\User
	 * @ORM\ManyToOne(targetEntity="User")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
	 */
	private $user;
	
	/**
	 * @var Entity\Firm
	 * @ORM\ManyToOne(targetEntity="Firm")
	 * @ORM\JoinColumn(name="firm_id", referencedColumnName="id", nullable=false)
	 */
	private $firm;
	
	
	/**
	 * @return string
	 */
	public function getRole()
	{
		return $this->role;
	}
	public function setRole($role)
	{
		$this->role = $role;
	}
	
	
	/**
	 * @return User
	 */
	public function getUser()
	{
		return $this->user;
	}
	public function setUser(User $user)
	{
		$this->user = $user;
	}
	
	
	/**
	 * @return Firm
	 */
    public function getFirm()
    {
    	return $this->firm;
    }
	public function setFirm(Firm $firm)
	{
		$this->firm = $firm;
	}
	
	
	
	
	
	
	
}
