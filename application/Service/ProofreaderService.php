<?php

namespace Service;

use Entity\Proofreader;
use Entity\User;

use Core\Acl\Acl;

use Core\Service\Params;
use Core\Service\ServiceBase;


class ProofreaderService extends ServiceBase
{
	
	/**
	 * @var Repository\ProofreaderRepository
	 * @Inject Repository\ProofreaderRepository
	 */
	private $proofreaderRepo;
	
	/**
	 * @var Repository\UserRepository
	 * @Inject Repository\UserRepository
	 */
	private $userRepo;
	
	
	public function _setupAcl()
	{
		$this->acl->allow(Acl::PROOFREADER, $this, 'deleteProofreader');
		$this->acl->allow(Acl::ADMIN, $this, 'deleteProofreader');
		$this->acl->allow(Acl::PROOFREADER, $this, 'updateProofreader');
	}
	
	/**
	 * Delete the proofreader entity of a certain user
	 */
	public function deleteProofreader(Proofreader $proofreader)
	{
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
			$this->validationFailed();
		}
		if($active != $proofreader->getActive()){
			if(null != $proofreader->getOrders()){
				$params->addMessage('active', 'There are still open orders');
				$this->validationFailed();
			}
			$proofreader->setActive($active);
		}
		
		//To do: Update Abilities!
		
	}
	
	/**
	 * Create a new proofreader entity and connect it to a certain user
	 */
	public function createProofreader(User $user)
	{
		$proofreader = new Proofreader($user);
		$this->persist($proofreader);
	}
	
}