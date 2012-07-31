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
	
	/**
	 * Name of the pricing scheme
	 * @ORM\Column(type="string", length=32, nullable=false)
	 */
	private $name;
	
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
	 * @return integer
	 */
	public function getDuration()
	{
		return $this->duration;
	}
	public function setDuration($duration)
	{
		$this->duration = $duration;
	}
	
	
	/**
	 * @return decimal  
	 */
	public function getFlatPrice()
	{
		return $this->flatPrice;
	}
	public function setFlatPrice($flatPrice)
	{
		$this->flatPrice = $flatPrice;
	}
	
	
	/**
	 * @return integer
	 */
	public function getMaxWords()
	{
		return $this->maxWords;
	}
	public function setMaxWords($maxWords)
	{
		$this->maxWords = $maxWords;
	}
	
	
	/**
	 * @return decimal  
	 */
	public function getWordPrice()
	{
		return $this->wordPrice;
	}
	public function setWordPrice($wordPrice)
	{
		$this->wordPrice = $wordPrice;
	}
	
}