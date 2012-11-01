<?php

namespace Entity;

use Core\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Repository\MemberRepository")
 * @ORM\Table(name="members")
 */
class Member extends BaseEntity
{
	const MEMBER	= 'member';
	const BOSS		= 'boss';
	
	
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
	 * @param User $user
	 * @param Firm $firm
	 */
	public function __construct(User $user, Firm $firm){
		parent::__construct();
		
		$this->user = $user;
		$this->firm = $firm;
	} 
	
	
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
	
	
	/**
	 * @return Firm
	 */
	public function getFirm()
    {
    	return $this->firm;
    }
		
}
