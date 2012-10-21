<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class BaseEntity
{
	/** 
	 * @ORM\Column(name="created_at", type="datetime") 
	 */
	private $createdAt;
	
	/**
	 * @ORM\Column(name="updated_at", type="datetime")
	 */
	private $updatedAt;
	
	/**
	 * @var string
	 * @ORM\Id
	 * @ORM\Column(name="id", type="string")
	 */
	private $id;
	
	
	/**
	 * @var Entity\UId
	 * @ORM\OneToOne(targetEntity="Entity\UId", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(name="id", referencedColumnName="id")
	 */
	private $uid;
	
	
	public function __construct()
	{
		/**
		 * The constructor sets the createTime and updateTime
		 * to a constant value (zero) which indicates that
		 * the Entity is not persisted yet.
		 */
		
		$this->createdAt = new \DateTime();
		$this->createdAt->setTimestamp(0);
	
		$this->updatedAt = new \DateTime();
		$this->updatedAt->setTimestamp(0);
		
		$this->uid = new \Entity\UId(get_class($this));
		$this->id = $this->uid->getId();
	}
	
	
	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}
	
	
	/**
	 * @ORM\PrePersist
	 */
	public function PrePersist()
	{
		$this->createdAt = new \DateTime("now");
		$this->updatedAt = new \DateTime("now");
	}
	
	/**
	 * @ORM\PreUpdate
	 */
	public function PreUpdate()
	{
		$this->updatedAt = new \DateTime("now");
	}
	
	
	/**
	 * @return \DateTime
	 */
	public function getUpdatedAt()
	{
		return $this->updatedAt;
	}
	
	
	/**
	 * @return \DateTime
	 */
	public function getCreatedAt()
	{
		return $this->createdAt;
	}
	
	
	public function __toString()
	{
		return "[" . get_class($this) . " :: " . $this->getId() . "]";
	}
}