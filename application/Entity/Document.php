<?php

namespace Entity;

use Core\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="documents")
 *
 */
class Document extends BaseEntity
{
	
	/**
	 * Name of the document
	 * @ORM\Column(type="string", length=128)
	 */
	private $name;
	
	/**
	 * Description of the document
	 * @ORM\Column(type="text")
	 */
	private $description;
	
	/**
	 * Location of the document
	 * @ORM\Column(type="string", length=128)
	 */
	private $location;
	
	
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
	 * @return text 
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
	 * @return string 
	 */
	public function getLocation()
	{
		return $this->location;
	}
	public function setLocation($location)
	{
		$this->location = $location;
	}
	
	
	
	
	
	
}