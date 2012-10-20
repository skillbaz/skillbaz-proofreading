<?php

namespace Service;

use Entity\Rating;
use Entity\Order;
use Entity\Proofreader;

use Core\Acl\Acl;

use Core\Service\ServiceBase;


class RatingService extends ServiceBase
{
	
	public function _setupAcl(){
		$this->acl->allow(Acl::CUSTOMER, $this, 'updateRating');
	}
	
	
	/**
	 * Create a rating autmatically
	 */
	public function createRating()
	{
		//Get the order and proofreader that are rated
		$order = $this->getContext()->getOrder();
		$proofreader = $order->getProofreader();
		
		//Create the rating entity
		$rating = new Rating($proofreader, $order);
		$this->persist($rating);
	}
	
	
	/**
	 * Update the rating for a closed order
	 */
	public function updateRating($grade)
	{
		$order = $this->getContext()->getOrder();
		
		//Check whether the order is closed
		if($order->getState() != Order::STATE_CLOSED){
			//Error: Order not yet closed
		}
		
		//Get the automatically generate rating
		$rating = $order->getRating();
		
		//Check whether the grade is already set
		if($rating->getGrade() != null){
			//Error: Grade already set
		}
		
		//Check whether the input is numeric
		if(!is_numeric($grade)){
			//Error: No numeric input
		}
		
		$rating->setGrade($grade);
		
	}
	
}