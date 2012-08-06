<?php

namespace Entity;

use Core\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="firms")
 *
 */
class Firm extends BaseEntity
{
	/**
	 * Name of the Firm
	 * @ORM\Column(type="string", length=64, nullable=false)
	 */
	private $name;
	
	/**
	 * Further details on Firms legal status or branch
	 * @ORM\Column(type="string", length=64, nullable=true)
	 */
	private $description;
	
	/**
	 * @var Entity\Address
	 * @ORM\OneToOne(targetEntity="Address")
	 * @ORM\JoinColumn(name="address_id", referencedColumnName="id", nullable=false) 
	 */
	private $address;
	
	
	/**
	 * @var \Doctrine\Common\Collections\ArrayCollection
	 * @ORM\OneToMany(targetEntity="Member", mappedBy="firm")
	 */
	private $members;
	
	
	/**
	 * A Firm has always an address
	 * 
	 * @param Address $address
	 */
	public function __construct(Address $address){
		parent::__construct();
		
		$this->address = $address;
	}
	
	
	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;	
	}
	public function setName($name)
	{
		$this->name = $name;
	}
	
	
	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}
	public function setDescription($description)
	{
		$this->description = $description;
	}
	
	
	/**
	 * @return Address
	 */
	public function getAddress()
	{
		return $this->address;
	}
	
	
	/**
	 * @return array
	 */
	public function getMembers()
	{
		return $this->members;
	}
	
}