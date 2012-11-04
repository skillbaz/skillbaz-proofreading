<?php

namespace Service;

use Entity\AccountsReceivable;

use Core\Acl\Acl;
use Core\Service\ServiceBase;


class AdministrationService extends ServiceBase
{
	
	/**
	 * @var Repository\AccountsReceivableRepository
	 * @Inject Repository\AccountsReceivableRepository
	 */
	private $accRecRepo;
	
	/**
	 * @var Repository\OrderRepository
	 * @Inject Repository\OrderRepository
	 */
	private $orderRepo;
	
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
	
	
	public function _setupAcl(){
		$this->acl->allow(Acl::ADMIN, $this, 'exportFinishedOrdersData');
		$this->acl->allow(Acl::ADMIN, $this, 'exportOrdersData');
		$this->acl->allow(Acl::ADMIN, $this, 'exportProofreadersData');
		$this->acl->allow(Acl::ADMIN, $this, 'exportUsersData');
	}
	
	
	/**
	 * Export all finished orders, which have not yet been exported
	 */
	public function exportFinishedOrdersData()
	{
		$accRecs = $this->accRecRepo->getAccountsReceivable(AccountsReceivable::UNEXPORTED);
		
			// To do: Visualize $accRecs and return the list
			
		foreach($accRecs as $accRec){
			$accRec->setState(AccountsReceivable::EXPORTED);
		}
	}
	
	/**
	 * Export all orders
	 */
	public function exportOrdersData()
	{
		$orders = $this->orderRepo->getAllOrders();
		
			// To do: Visualize nicely and return the list
	}
	
	/**
	 * Export all data from the proofreaders
	 */
	public function exportProofreadersData()
	{
		$proofreaders = $this->proofreaderRepo->getAllProofreaders();
		
			// To do: Calculate their rating data, visualize nicely and return
	}
	
	/**
	 * Export all data from the users
	 */
	public function exportUsersData()
	{
		$users = $this->userRepo->getAllUsers();
		
			// To do: Visualize nicely and return
	}
	
}