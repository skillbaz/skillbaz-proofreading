<?php

namespace Entity;

use Core\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Repository\FieldRepository")
 * @ORM\Table(name="fields")
 *
 */
class Field extends BaseEntity
{
	
	public function __construct($name, $description)
	{
		parent::__construct();
		
		$this->name = $name;
		$this->description = $description;
	}
	
	
	/**
	 * Indicates whether this particular field is still available
	 * @ORM\Column(type="boolean")
	 */
	private $active;
	
	/**
	 * @ORM\Column(type="string", length=64, nullable=false)
	 */
	private $name;
	
	/**
	 * @ORM\Column(type="text")
	 */
	private $description;
	
	
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
	 * @return string 
	 */
	public function getName()
	{
		return $this->name;
	}
	
	
	/**
	 * @return text  
	 */
	public function getDescription()
	{
		return $this->description;
	}
}