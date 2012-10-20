<?php

namespace Service;

use Entity\Proofreader;

use Core\Acl\Acl;

use Core\Service\Params;
use Core\Service\ServiceBase;

class ProofreaderService extends ServiceBase
{
	public function _setupAcl()
	{
		$this->acl->allow(Acl::PROOFREADER, $this, 'deleteProofreader');
		$this->acl->allow(Acl::PROOFREADER, $this, 'updateProofreader');
	}
	
	/**
	 * Delete the proofreader entity of a certain user
	 */
	public function deleteProofreader()
	{
		$proofreader = $this->getContext()->getProofreader();
		if(null != $proofreader->getOrders()){
			//Error: There are still open orders
		}
		$this->remove($proofreader);
	}
	
	/**
	 * Update the information of a proofreader
	 * @param Params $params
	 */
	public function updateProofreader(Params $params)
	{
		$proofreader = $this->getContext()->getProofreader();
		
		//Check and update the status of the proofreader
		$active = $params->getValue('active');
		if(!is_bool($active)){
			$params->addMessage('active', 'This is not a valid entry');
		}
		elseif($active != $proofreader->getActive()){
			$proofreader->setActive($active);
		}
		
		//To do: Update Abilities!
		
	}
	
	/**
	 * Create a new proofreader entity and connect it to a certain user
	 */
	public function createProofreader()
	{
		$user = $this->getContext()->getUser();
		$proofreader = new Proofreader($user);
		$this->persist($proofreader);
	}
	
	
	
}