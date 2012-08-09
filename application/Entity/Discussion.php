<?php

namespace Entity;

use Core\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="discussions")
 *
 */
class Discussion extends BaseEntity
{

	public function __construct(User $user, Correction $correction, $comment)
	{
		parent::__construct();
		
		$this->user = $user;
		$this->correction = $correction;
		$this->comment = $comment;
	}
	
	
	/**
	 * The User who made the comment
	 * @var Entity\User
	 * @ORM\ManyToOne(targetEntity="User")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
	 */
	private $user;
	
	/**
	 * The correction on which the comment is made
	 * @var Entity\Correction
	 * @ORM\ManyToOne(targetEntity="Correction")
	 * @ORM\JoinColumn(name="correction_id", referencedColumnName="id", nullable=false)
	 */
	private $correction;
	
	/**
	 * @ORM\Column(type="text")
	 */
	private $comment;
	
	
	/**
	 * @return User  
	 */
	public function getUser()
	{
		return $this->user;
	}
	
	/**
	 * @return Correction  
	 */
	public function getCorrection()
	{
		return $this->correction;
	}
	
	/**
	 * @return text  
	 */
	public function getComment()
	{
		return $this->comment;
	}
	
}