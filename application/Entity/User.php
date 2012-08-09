<?php

namespace Entity;

use Core\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 *
 */
class User extends BaseEntity
{
	
	/**
	 * e-mail address, unique
	 * @ORM\Column(type="string", length=64, nullable=true, unique=true )
	 */
	private $email;
	
	
	/**
	 * Indicates whether the User is active
	 * @ORM\Column(type="boolean")
	 */
	private $active;
	
	
	/**
	 * @var Entity\Login
	 * @ORM\OneToOne(targetEntity="Login", mappedBy="user")
	 */
	private $login;
	
	
	/**
	 * @var \Doctrine\Common\Collections\ArrayCollection
	 * @ORM\OneToMany(targetEntity="Member", mappedBy="user")
	 */
	private $members;
	
	
	/**
	 * @ORM\Column(type="string", length=32, nullable=true)
	 */
	private $firstname;
	
	
	/**
	 * @ORM\Column(type="string", length=32, nullable=true)
	 */
	private $surname;
	
	
	/**
	 * @ORM\Column(type="string", length=64, nullable=true)
	 */
	private $skype;
	
	
	/**
	 * Activation Code used to verify e-mail address
	 * @ORM\Column(type="string", length=64, nullable=true)
	 * @var string
	 */
	private $acode;
	
	
	/**
	 * Preferred language
	 * @ORM\Column(type="string", length=32, nullable=true)
	 */
	private $preflanguage;
	
	
	/**
	 * @var Entity\Legi
	 * @ORM\OneToOne(targetEntity="Legi", mappedBy="user")
	 * @ORM\JoinColumn(name="legi_id", referencedColumnName="id")
	 */
	private $legi;
	
	
	/**
	 * @var \Doctrine\Common\Collections\ArrayCollection
	 * @ORM\OneToMany(targetEntity="Order", mappedBy="user")
	 */
	private $orders;
	
	
	/**
	 * @var Entity\Address
	 * @ORM\OneToOne(targetEntity="Address", mappedBy="user")
	 * @ORM\JoinColumn(name="address_id", referencedColumnName="id")
	 */
	private $address;
	
	
	/**
	 * @var \Doctrine\Common\Collections\ArrayCollection
	 * @ORM\OneToMany(targetEntity="Discussion", mappedBy="user")
	 */
	private $discussions;
	
	
	/**
	 * @var Entity\Proofreader
	 * @ORM\OneToOne(targetEntity="Proofreader", mappedBy="user")
	 */
	private $proofreader;
	
	
	/**
	 * @return string
	 */
	public function getEmail(){
		return $this->email;
	}	
	public function setEmail($email){
		$this->email = $email;
	}
	
	
	/**
	 * @return boolean
	 */
	public function getActive(){
		return $this->active;
	}	
	public function setActive($active = true){
		$this->active = $active;
	}
		
	
	/**
	 * @return Login
	 */
	public function getLogin()
	{
		return $this->login;
	}
	
	
	/**
	 * @return array
	 */
	public function getMembers(){
		return $this->members;
	}
	
	
	/**
	 * @return string
	 */
	public function getFirstname(){
		return $this->firstname;
	}	
	public function setFirstname($firstname){
		$this->firstname = $firstname;
	}
	

	/**
	 * @return string
	 */
	public function getSurname(){
		return $this->surname;
	}	
	public function setSurname($surname){
		$this->surname = $surname;
	}
	

	/**
	 * @return string
	 */
	public function getSkype(){
		return $this->skype;
	}	
	public function setSkype($skype){
		$this->skype = $skype;
	}
	
	
	/**
	 * Create a new Activation Code
	 */
	public function createAcode()
	{
		$this->acode = md5(unique(microtime(true)));
		return $this->acode;
	}
	
	public function resetAcode(){
		$this->acode = null;
	}
	
	/**
	 * Activates User if acode is correct.
	 * Returns boolean whether activation was successful.
	 * 
	 * @param string $acode
	 * @return bool
	 */
	public function activate($acode){
		if($this->acode == $acode){
			$this->setActive();
			$this->resetAcode();
			return true;
		}
		
		return false;
	}
	

	/**
	 * @return string
	 */
	public function getPrefLanguage(){
		return $this->preflanguage;
	}	
	public function setPrefLanguage($preflanguage){
		$this->preflanguage = $preflanguage;
	}
	
	
	/**
	 * @return Legi
	 */
	public function getLegi(){
		return $this->legi;
	}	
	
	/**
	 * Set the Legi of this User Entity
	 */
	public function setLegi(Legi $legi){
		$this->legi = $legi;
	}
	
	
	/**
	 * Returns whether the User has access to Student-Prices
	 * @return bool
	 */
	public function isStudent(){
		if(is_null($this->legi)){
			// If User has no Legi, the User is not a Student
			return false;
		}
		return $this->legi->isValid();
	}
	
	
	/**
	 * @return array
	 */
	public function getOrders(){
		return $this->orders;
	}


	/**
	 * @return Address
	 */
	public function getAddress(){
		return $this->address;
	}	

	/**
	 * Set the Address of this User Entity
	 */
	public function setAddress(Address $address){
		$this->address = $address;
	}
	
		
	/**
	 * @return array
	 */
	public function getDiscussions(){
		return $this->discussions;
	}
	
	
	/**
	 * @return Proofreader
	 */
	public function getProofreader(){
		return $this->proofreader;
	}
}