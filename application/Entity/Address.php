<?php

namespace Entity;

use Core\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="addresses")
 *
 */
class Address extends BaseEntity
{
	/**
	 * @ORM\Column(type="string", length=64, nullable=false)
	 */
	private $street;
	
	/**
	 * @ORM\Column(type="string", length=16, nullable=false)
	 */
	private $zipcode;
	
	/**
	 * @ORM\Column(type="string", length=64, nullable=false)
	 */
	private $city;
	
	/**
	 * @ORM\Column(type="string", length=64, nullable=false)
	 */
	private $country;
	
	
	/**
	 * @return string
	 */
	public function getStreet()
	{
		return $this->street;
	}
	public function setStreet($street)
	{
		$this->street = $street;
	}
	
	
	/**
	 * @return string
	 */
	public function getZipcode()
	{
		return $this->zipcode;
	}
	public function setZipcode($zipcode)
	{
		$this->zipcode = $zipcode;
	}
	
	
	/**
	 * @return string
	 */
	public function getCity()
	{
		return $this->city;
	}
	public function setCity($city)
	{
		$this->city = $city;
	}
	
	
	/**
	 * @return string
	 */
	public function getCountry()
	{
		return $this->country;
	}
	public function setCountry($country)
	{
		$this->country = $country;
	}
	
	
	
}