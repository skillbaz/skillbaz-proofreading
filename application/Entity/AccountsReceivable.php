<?php

namespace Entity;

use Core\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="accounts_receivable")
 *
 */
class AccountsReceivable extends BaseEntity
{
	
	const EXPORTED 		= 'exported';
	const UNEXPORTED 	= 'unexported';
	
	public function __construct(Order $order)
	{
		parent::__construct();
		$this->order = $order;
	}
	
	/**
	 * Indicates whether the data is exported or not
	 * @ORM\Column(type="string", length=32, nullable=false)
	 */
	private $state;
	
	/**
	 * Final price of the order after settlement
	 * @ORM\Column(type="decimal", precision=8, scale=2, nullable=false)
	 */
	private $settledPrice;
	
	 /**
	  * Salary the proofreader receives
	  * @ORM\Column(type="decimal", precision=8, scale=2, nullable=false)
	  */
	private $proofreaderSalarySettled;
	
	/**
	 * Corresponding order
	 * @var Entity\Order
	 * @ORM\OneToOne(targetEntity="Order")
	 * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
	 */
	private $order;
	
	
	/**
	 * @return string 
	 */
	public function getState()
	{
		return $this->state;
	}
	public function setState($state)
	{
		$this->state = $state;
	}
	
	
	/**
	 * @return decimal  
	 */
	public function getSettledPrice()
	{
		return $this->settledPrice;
	}
	public function setSettledPrice($settledPrice)
	{
		$this->settledPrice = $settledPrice;
	}
	
	
	/**
	 * @return decimal 
	 */
	public function getProofreaderSalarySettled()
	{
		return $this->proofreaderSalarySettled;
	}
	public function setProofreaderSalarySettled($proofreaderSalarySettled)
	{
		$this->proofreaderSalarySettled = $proofreaderSalarySettled;
	}
	
	
	/**
	 * @return Order  
	 */
	public function getOrder()
	{
		return $this->order;
	}
	
}