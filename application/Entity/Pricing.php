<?php

namespace Entity;

use Core\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="pricings")
 *
 */
class Pricing extends BaseEntity
{
	
	public function __construct($duration, $flatPrice, $maxWords, $wordPrice)
	{
		parent::__construct();
		$this->duration = $duration;
		$this->flatPrice = $flatPrice;
		$this->maxWords = $maxWords;
		$this->wordPrice = $wordPrice;
	}
	
	
	/**
	 * Indicates whether this pricing scheme is active
	 * @ORM\Column(type="boolean")
	 */
	private $active;
	
	/**
	 * Name of the pricing scheme
	 * @ORM\Column(type="string", length=32, nullable=false)
	 */
	private $name;
	
	/**
	 * Description of the pricing scheme
	 * @ORM\Column(type="text")
	 */
	private $description;
	
	/**
	 * Duration until order must be delivered
	 * @ORM\Column(type="integer", nullable=false)
	 */
	private $duration;
	
	/**
	 * Flat price
	 * @ORM\Column(type="decimal", precision=8, scale=2)
	 */
	private $flatPrice;
	
	/**
	 * Maximum amount of words for this pricing scheme
	 * @ORM\Column(type="integer")
	 */
	private $maxWords;
	
	/**
	 * Price per word
	 * @ORM\Column(type="decimal", precision=3, scale=2)
	 */
	private $wordPrice;
	
	
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
	 * @return integer
	 */
	public function getDuration()
	{
		return $this->duration;
	}
	
	
	/**
	 * @return decimal  
	 */
	public function getFlatPrice()
	{
		return $this->flatPrice;
	}
	
	
	/**
	 * @return integer
	 */
	public function getMaxWords()
	{
		return $this->maxWords;
	}

	
	/**
	 * @return decimal  
	 */
	public function getWordPrice()
	{
		return $this->wordPrice;
	}
	
	
	/**
	 * Calculates the Price
	 * 
	 * @param integer $wordCount
	 */
	public function calculatePrice($wordCount)
	{
		return 	$this->flatPrice 
			+ 	$wordCount * $this->wordPrice;
	}
	
	
	/**
	 * Calculcates the Salary
	 * 
	 * @param integer $wordCount
	 */
	public function calculateSalary($wordCount)
	{
		// TODO!!
	}

}