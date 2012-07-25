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
	
	
//	/**
//	 * @var Entity\Member
//	 * @ORM\OneToOne(targetEntity="Member", mappedBy="user")
//	 */
//	private $member;
	
	
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
	
	
//	/**
//	 * @var Entity\Order
//	 * @ORM\OneToMany(targetEntity="Order", mappedBy="user")
//	 */
//	private $orders;
	
	
//	/**
//	 * @var Entity\Address
//	 * @ORM\OneToOne(targetEntity="Address", mappedBy="user")
//	 * @ORM\JoinColumn(name="address_id", referencedColumnName="id")
//	 */
//	private $address;
	
	
//	/**
//	 * @var Entity\Discussion
//	 * @ORM\OneToMany(targetEntity="Discussion", mappedBy="user")
//	 */
//	private $discussions;
	
	
//	/**
//	 * @var Entity\Proofreader
//	 * @ORM\OneToOne(targetEntity="Proofreader", mappedBy="user")
//	 */
//	private $proofreader;
	
	
	
	
	public function setEmail($email){
		$this->email = $email;
	}
	
	public function getEmail(){
		return $this->email;
	}
	
	
	public function setActive($active = true){
		$this->active = $active;
	}
		
	public function setInactive($active = false){
		$this->active = $active;
	}
		
	public function getActive(){
		return $this->active;
	}
	
	
	/**
	 * @return Login
	 */
	public function getLogin()
	{
		return $this->login;
	}
	
	
//	/**
//	 * @return Member
//	 */
//	public function getMember()
//	{
//		return $this->member;
//	}
	
	
	public function setFirstname($firstname){
		$this->firstname = $firstname;
	}
	
	public function getFirstname(){
		return $this->firstname;
	}
	
	
	public function setSurname($surname){
		$this->surname = $surname;
	}
	
	public function getSurname(){
		return $this->surname;
	}
	
	
	public function setSkype($skype){
		$this->skype = $skype;
	}
	
	public function getSkype(){
		return $this->skype;
	}
	
	
	/**
	 * Create a new Activation Code
	 */
	public function createAcode()
	{
		$this->acode = md5(unique(microtime(true)));
		return $this->acode;
	}
	
	public function getAcode(){
		return $this->acode;
	}
	
	public function checkAcode($acode)
	{
		$code = $acode;
		return $code == $this->acode;
	}
	
	public function resetAcode(){
		$this->acode = null;
	}
	

	public function setPrefLanguage($preflanguage){
		$this->preflanguage = $preflanguage;
	}
	
	public function getPrefLanguage(){
		return $this->preflanguage;
	}
	
	
	/**
	 * Set the Legi of this User Entity
	 */
	public function setLegi(Legi $legi){
		$this->legi = $legi;
	}
	
	
	/**
	 * @return Legi
	 */
	public function getLegi(){
		return $this->legi;
	}
	
	
//	/**
//	 * @return Order
//	 */
//	public function getOrders(){
//		return $this->orders;
//	}
	
//	/**
//	 * Set the Address of this User Entity
//	 */
//	public function setAddress(Address $address){
//		$this->address = $address;
//	}
	
//	/**
//	 * @return Address
//	 */
//	public function getAddress(){
//		return $this->address;
//	}
	
		
//	/**
//	 * @return Discussion
//	 */
//	public function getDiscussions(){
//		return $this->discussions;
//	}
	
	
//	/**
//	 * @return Proofreader
//	 */
//	public function getProofreader(){
//		return $this->proofreader;
//	}
}