<?php

namespace Entity;

use Core\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ratings")
 *
 */
class Rating extends BaseEntity
{
	const GRADE_UNUSABLE 	= 1;
	const GRADE_POOR 		= 2;
	const GRADE_FLAWED 		= 3;
	const GRADE_SUFFICIENT 	= 4;
	const GRADE_GOOD 		= 5;
	const GRADE_EXCELLENT 	= 6;
	
	
	public function __construct(Proofreader $proofreader, Order $order)
	{
		$this->proofreader = $proofreader;
		$this->order = $order;
	}
	
	/**
	 * Indicates whether the rating was automatically generated
	 * @ORM\Column(type="boolean", nullable=false)
	 */
	private $auto;
	
	/**
	 * The grade that was awarded for this order
	 * @ORM\Column(type="integer", nullable=false)
	 */
	private $grade;
	
	/**
	 * The comment for this rating
	 * @ORM\Column(type="text")
	 */
	private $comment;
	
	/**
	 * The responsible proofreader
	 * @var Entity\Proofreader
	 * @ORM\ManyToOne(targetEntity="Proofreader")
	 * @ORM\JoinColumn(name="proofreader_id", referencedColumnName="id", nullable=false)
	 */
	private $proofreader;
	
	/**
	 * The corresponding order
	 * @var Entity\Order
	 * @ORM\OneToOne(targetEntity="Order")
	 * @ORM\JoinColumn(name="order_id", referencedColumnName="id", nullable=true)
	 */
	private $order;
	
	
	/**
	 * @return boolean  
	 */
	public function getAuto()
	{
		return $this->auto;
	}
	public function setAuto($auto = true)
	{
		$this->auto = $auto;
	}
	
	
	/**
	 * @return integer 
	 */
	public function getGrade()
	{
		return $this->grade;
	}
	public function setGrade($grade)
	{
		$this->grade = $grade;
	}
	
	
	/**
	 * @return text 
	 */
	public function getComment()
	{
		return $this->comment;
	}
	public function setComment($comment)
	{
		$this->comment = $comment;
	}
	
	
	/**
	 * @return Proofreader  
	 */
	public function getProofreader()
	{
		return $this->proofreader;
	}
	
	
	/**
	 * @return Order  
	 */
	public function getOrder()
	{
		return $this->order;
	}
	
}