<?php

namespace Core\Entity;

/**
 * @MappedSuperclass
 * @HasLifecycleCallbacks
 */
class Base
{
	/** 
	 * @Column(name="created_at", type="datetime") 
	 */
	private $createdAt;
	
	/**
	 * @Column(name="updated_at", type="datetime")
	 */
	private $updatedAt;
	
	/**
	 * @var string
	 * @Id
	 * @Column(name="id", type="string")
	 */
	protected $id;
	
	
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
	}
	
	
	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}
	
	
	/**
	 * @PrePersist
	 */
	public function PrePersist()
	{
		$this->createdAt = new \DateTime("now");
		$this->updatedAt = new \DateTime("now");
	}
	
	/**
	 * @PreUpdate
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