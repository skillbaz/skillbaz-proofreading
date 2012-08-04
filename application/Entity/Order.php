<?php

namespace Entity;

use Core\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="orders")
 *
 */
class Order extends BaseEntity
{
	const STATE_OFFERED 	= 'offered';
	const STATE_OPEN 		= 'open';
	const STATE_WORKING		= 'working';
	const STATE_COMMITTED 	= 'committed';
	const STATE_DELIVERED 	= 'delivered';
	const STATE_REJECTED  	= 'rejected';
	const STATE_CLOSED		= 'closed';
	
	public function __construct(User $user, Address $address, Document $document, Pricing $pricing, Field $field)
	{
		parent::__construct();
		$this->iteration = 0;
		$this->state = self::STATE_OFFERED;
		
		$this->user = $user;
		$this->address = $address;
		$this->finalDocument = $document;
		$this->pricing = $pricing;
		$this->field = $field;

		
		$wordCount = $document->getWordCount();
		$this->offeredPrice = $pricing->calculatePrice($wordCount);
		$this->settledPrice = $this->offeredPrice;
		
		$this->proofreaderSalaryOffered = $pricing->calculateSallery($wordCount);
		$this->proofreaderSalarySettled = $this->proofreaderSalaryOffered; 
	}
	
	/**
	 * Current state of the order
	 * @ORM\Column(type="string", length=32, nullable=false)
	 */
	private $state;
	
	/**
	 * Number of iterations the order went through
	 * @ORM\Column(type="integer")
	 */
	private $iteration;
	
	/**
	 * Original price agreed
	 * @ORM\Column(type="decimal", precision=8, scale=2)
	 */
	private $offeredPrice;
	
	/**
	 * Changed price after customer intervention
	 * @ORM\Column(type="decimal", precision=8, scale=2)
	 */
	private $settledPrice;
	
	/**
	 * Original salary for proofreader based on offered price
	 * @ORM\Column(type="decimal", precision=8, scale=2)
	 */
	private $proofreaderSalaryOffered;
	
	/**
	 * Proofreader salary based on settled price
	 * @ORM\Column(type="decimal", precision=8, scale=2)
	 */
	private $proofreaderSalarySettled;
	
	/**
	 * Customer's reason for rejecting the first correction
	 * @ORM\Column(type="text")
	 */
	private $description;
	
	/**
	 * Admins public comment after intervention service
	 * @ORM\Column(type="text")
	 */
	private $publicComment;
	
	/**
	 * Admins internal comment for the proofreader after intervention service
	 * @ORM\Column(type="text")
	 */
	private $internalComment;
	
	/**
	 * The pricing scheme which was chosen for this offer
	 * @var Entity\Pricing
	 * @ORM\ManyToOne(targetEntity="Pricing")
	 * @ORM\JoinColumn(name="pricing_id", referencedColumnName="id", nullable=false)
	 */
	private $pricing;
	
	/**
	 * The user who is responsible for this order
	 * @var Entity\User
	 * @ORM\ManyToOne(targetEntity="User")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
	 */
	private $user;
	
	/**
	 * The billing address for this order 
	 * @var Entity\Address
	 * @ORM\ManyToOne(targetEntity="Address")
	 * @ORM\JoinColumn(name="billing_address_id", referencedColumnName="id", nullable=false)
	 */
	private $address;
	
	/**
	 * The original document that has to be corrected
	 * @var Entity\Document
	 * @ORM\OneToOne(targetEntity="Document")
	 * @ORM\JoinColumn(name="original_document_id", referencedColumnName="id")
	 */
	private $originalDocument;
	
	/**
	 * The corrected or final document
	 * @var Entity\Document
	 * @ORM\OneToOne(targetEntity="Document")
	 * @ORM\JoinColumn(name="final_document_id", referencedColumnName="id")
	 */
	private $finalDocument;
	
//	/**
//	 * The responsible proofreader for this order
//	 * @var Entity\Proofreader
//	 * @ORM\ManyToOne(targetEntity="Proofreader")
//	 * @ORM\JoinColumn(name="proofreader_id", referencedColumnName="id")
//	 */
//	private $proofreader;
	
//	/**
//	 * The field of expertise which is required for this order
//	 * @var Entity\Field
//	 * @ORM\ManyToOne(targetEntity="Field")
//	 * @ORM\JoinColumn(name="field_id", referencedColumnName="id")
//	 */
//	private $field;
	
//	/**
//	 * The current correction for this order
//	 * @var Entity\Correction
//	 * @ORM\OneToOne(targetEntity="Correction", mappedBy="order")
//	 */
//	private $correction;
	
	/**
	 * The logs for this order
	 * @var \Doctrine\Common\Collections\ArrayCollection
	 * @ORM\OneToMany(targetEntity="OrderLog", mappedBy="order")
	 */
	private $orderLogs;
	
//	/**
//	 * The rating that was awarded to this order
//	 * @var Entity\Rating
//	 * @ORM\OneToOne(targetEntity="Rating", mappedBy="order")
//	 */
//	private $rating;
	
	
	
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
	 * @return integer
	 */
	public function getIteration()
	{
		return $this->iteration;
	}
	public function setIteration($iteration)
	{
		$this->iteration = $iteration;
	}
	
	
	/**
	 * @return decimal 
	 */
	public function getOfferedPrice()
	{
		return $this->offeredPrice;
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
	public function getProofreaderSalaryOffered()
	{
		return $this->proofreaderSalaryOffered;
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
	 * @return text 
	 */
	public function getPublicComment()
	{
		return $this->publicComment;
	}
	public function setPublicComment($publicComment)
	{
		$this->publicComment = $publicComment;
	}
	
	
	/**
	 * @return text 
	 */
	public function getInternalComment()
	{
		return $this->internalComment;
	}
	public function setInternalComment($internalComment)
	{
		$this->internalComment = $internalComment;
	}
	
	
	/**
	 * @return Pricing
	 */
	public function getPricing()
	{
		return $this->pricing;
	}
	public function setPricing(Pricing $pricing)
	{
		$this->pricing = $pricing;
	}
	
	
	/**
	 * @return User
	 */
	public function getUser()
	{
		return $this->user;
	}
	
	
	/**
	 * @return Address
	 */
	public function getAddress()
	{
		return $this->address;
	}
	
	
	/**
	 * @return Document
	 */
	public function getOriginalDocument()
	{
		return $this->originalDocument;
	}
	
	
	/**
	 * @return Document
	 */
	public function getFinalDocument()
	{
		return $this->finalDocument;
	}
	public function setFinalDocument(Document $document)
	{
		$this->finalDocument = $document;
	}
	
	
//	/**
//	 * @return Proofreader
//	 */
//	public function getProofreader()
//	{
//		return $this->proofreader;
//	}
//	public function setProofreader(Proofreader $proofreader)
//	{
//		$this->proofreader = $proofreader;
//	}

	
//	/**
//	 * @return Field 
//	 */
//	public function getField()
//	{
//		return $this->field;
//	}
	
	
//	/**
//	 * @return Correction
//	 */
//	public function getCorrection()
//	{
//		return $this->correction;
//	}
	
	
	/**
	 * @return array
	 */
	public function getOrderLogs()
	{
		return $this->orderLogs;
	}
	
	
//	/**
//	 * @return Rating
//	 */
//	public function getRating()
//	{
//		return $this->rating;
//	}
	
		
}