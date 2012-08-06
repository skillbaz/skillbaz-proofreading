<?php

namespace Entity;

use Core\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="proofreaders")
 *
 */
class Proofreader extends BaseEntity
{
	
	public function __construct(User $user)
	{
		parent::__construct();
		$this->user = $user;
		$this->active = true;
	}
	
	/**
	 * Indicator whether the User is currently active as proofreader
	 * @ORM\Column(type="boolean")
	 */
	private $active;
	
	/**
	 * The corresponding User Entity behind this proofreader
	 * @var Entity\User
	 * @ORM\OneToOne(targetEntity="User")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
	 */
	private $user;
	
	/**
	 * @var \Doctrine\Common\Collections\ArrayCollection
	 * @ORM\OneToMany(targetEntity="Ability", mappedBy="proofreader")
	 */
	private $abilities;
	
	/**
	 * @var \Doctrine\Common\Collections\ArrayCollection
	 * @ORM\OneToMany(targetEntity="Rating", mappedBy="proofreader")
	 */
	private $ratings;
	
	
	/**
	 * @var \Doctrine\Common\Collections\ArrayCollection
	 * @ORM\OneToMany(targetEntity="Order", mappedBy="proofreader")
	 */
	private $orders;
	
	
	/**
	 * @return boolean 
	 */
	public function getActive()
	{
		return $this->active;
	}
	public function setActive($active = true)
	{
		$this->active = $active;
	}
	
	
	/**
	 * @return User  
	 */
	public function getUser()
	{
		return $this->user;
	}
	
	/**
	 * @return array 
	 */
	public function getAbilities()
	{
		return $this->abilities;
	}
	
	/**
	 * @return array  
	 */
	public function getRatings()
	{
		return $this->ratings;
	}
	
	/**
	 * @return array 
	 */
	public function getOrders()
	{
		return $this->orders;
	}
	
	
}