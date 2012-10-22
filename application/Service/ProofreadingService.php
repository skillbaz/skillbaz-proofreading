<?php

namespace Service;

use Entity\Discussion;

use Entity\Order;
use Entity\Correction;
use Core\Acl\Acl;
use Core\Service\ServiceBase;

class ProofreadingService extends ServiceBase
{
	/**
	 * @var Service\OrderLogService
	 * @Inject Service\OrderLogService
	 */
	private $logService;
	
	/**
	 * @var Service\DocumentService
	 * @Inject Service\DocumentService
	 */
	private $documentService;
	
	/**
	 * @var Repository\CorrectionRepository
	 * @Inject Repository\CorrectionRepository
	 */
	private $correctionRepo;
	
	
	public function _setupAcl(){
		$this->acl->allow(Acl::PROOFREADER, $this, 'acceptOrder');
		$this->acl->allow(Acl::RESPONSIBLE_PROOFREADER, $this, 'uploadCorrection');
		$this->acl->allow(Acl::RESPONSIBLE_PROOFREADER, $this, 'commitCorrection');
		$this->acl->allow(Acl::RESPONSIBLE_PROOFREADER, $this, 'commentCorrectionReaction');
		$this->acl->allow(Acl::CUSTOMER, $this, 'commentCorrectionReaction');
		$this->acl->allow(Acl::ADMIN, $this, 'commentCorrectionReaction');
	}
	
	
	/**
	 * Accepts an order and links it to the accepting proofreader
	 */
	public function acceptOrder()
	{
		$order = $this->getContext()->getOrder();
		$order->setState(Order::STATE_WORKING);
		
		//Set the proofreader for the respective order
		$order->setProofreader($this->getContext()->getProofreader());
		
		//Create log entry
		$this->logService->orderAccepted();
	}
	
	/**
	 * Uploads a correction
	 */
	public function uploadCorrection($params)
	{
		//Get the relevant information
		$document = $this->documentService->createDocument($params);
		$order = $this->getContext()->getOrder();
		$proofreader = $this->getContext()->getProofreader();
		
		//Check whether there is already a correction for this order iteration
		$correction = $this->correctionRepo->getRecentCorrection($order);
		if( $correction != null && $correction->getVersion() == $order->getIteration()){
			
			//If yes, just update the document and the responsible proofreader
			$correction->setDocument($document);
			$correction->setProofreader($proofreader);
		}
		else{
			//If not, create a new correction and set the proofreader accordingly
			$correction = new Correction($order, $document);
			$correction->setProofreader($proofreader);
			$this->persist($correction);
		}
		
		//Create log entry
		$this->logService->correctionUploaded($correction);
	}
	
	/**
	 * Commits a correction to the user
	 */
	public function commitCorrection()
	{
		$order = $this->getContext()->getOrder();
		$order->setState(Order::STATE_COMMITTED);
		
		//Get the most recent correction on the order
		$correction = $this->correctionRepo->getRecentCorrection($order);
		
		//Update the commit time to now
		$correction->updateCommitTime();
		
			//To do: Send Mail to customer
			
		//Set the proofreader for this specific correction
		$correction->setProofreader($this->getContext()->getProofreader());
		
		//Create log entry
		$this->logService->correctionCommitted($correction);
	}
	
	/**
	 * Makes a comment on a correction reaction from the customer
	 */
	public function commentCorrectionReaction($comment)
	{
		$user = $this->getContext()->getUser();
		$order = $this->getContext()->getOrder();
		
		//Get the most recent correction
		$correction = $this->correctionRepo->getRecentCorrection($order);
		
		//Create a new discussion entity
		$discussion = new Discussion($user, $correction, $comment);
		$this->persist($discussion);
		
		//Create log entry
		$this->logService->correctionReactionCommented($correction, $discussion);
	}
	
}