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
	
	public function __construct($name, $description, $location,	$wordCount)
	{
		if(! is_file($location)){
			// $location is not a file!
			throw new \Exception("[$location] is not a file");
		}
			
		parent::__construct();
		$this->name = $name;
		$this->description = $description;
		$this->location = $location;
		$this->wordCount = $wordCount;
	}
	
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
	 * Number of words
 	 * @ORM\Column(type="integer")
	 */
	private $wordCount;
	
	
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
	
	
	/**
	 * @return string 
	 */
	public function getLocation()
	{
		return $this->location;
	}
	
	
	/**
	 * @return integer
	 */
	public function getWordCount()
	{
		return $this->wordCount;
	}
	
}