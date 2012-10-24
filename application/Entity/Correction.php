<?php

namespace Entity;

use Core\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Repository\CorrectionRepository")
 * @ORM\Table(name="corrections")
 *
 */
class Correction extends BaseEntity
{
	
	public function __construct(Order $order, Document $document)
	{
		parent::__construct();
		
		$this->order = $order;
		$this->document = $document;
		
		$this->version = $order->getIteration();
		$this->commitTime = null;
	}
	
	
	/**
	 * The time at which the corrected document was made available for the customer
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	private $commitTime;
	
	/**
	 * The version number of the correction
	 * @ORM\Column(type="integer", nullable=false)
	 */
	private $version;
	
	/**
	 * The corresponding order
	 * @var Entity\Order
	 * @ORM\ManyToOne(targetEntity="Order")
	 * @ORM\JoinColumn(name="order_id", referencedColumnName="id", nullable=false)
	 */
	private $order;
	
	/**
	 * The corrected document
	 * @var Entity\Document
	 * @ORM\OneToOne(targetEntity="Document")
	 * @ORM\JoinColumn(name="corrected_document_id", referencedColumnName="id", nullable=false)
	 */
	private $document;
	
	/**
	 * The proofreader who is responsible for this correction
	 * @var Entity\Proofreader
	 * @ORM\ManyToOne(targetEntity="Proofreader")
	 * @ORM\JoinColumn(name="proofreader_id", referencedColumnName="id", nullable=true)
	 */
	private $proofreader;
	
	/**
	 * The corresponding discussions to this correction
	 * @var \Doctrine\Common\Collections\ArrayCollection
	 * @ORM\OneToMany(targetEntity="Discussion", mappedBy="correction", cascade={"remove"})
	 */
	private $discussions;
	
	
	/**
	 * @return datetime  
	 */
	public function getCommitTime()
	{
		return $this->commitTime;
	}
	public function updateCommitTime()
	{
		$this->commitTime = new \DateTime("now");
	}
	
	/**
	 * @return integer 
	 */
	public function getVersion()
	{
		return $this->version;
	}
	
	/**
	 * @return Order  
	 */
	public function getOrder()
	{
		return $this->order;
	}
	
	/**
	 * @return Document  
	 */
	public function getDocument()
	{
		return $this->document;
	}
	public function setDocument(Document $document)
	{
		$this->document = $document;
	}
	
	/**
	 * @return Proofreader  
	 */
	public function getProofreader()
	{
		return $this->proofreader;
	}
	public function setProofreader(Proofreader $proofreader)
	{
		$this->proofreader = $proofreader;
	}
	
	/**
	 * @return array  
	 */
	public function getDiscussions()
	{
		return $this->discussions;
	}
	
}