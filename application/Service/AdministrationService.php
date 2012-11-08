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
		$this->acl->allow(Acl::ADMIN, $this, 'markFinishedOrdersForExport');
		$this->acl->allow(Acl::ADMIN, $this, 'markForExport');
		$this->acl->allow(Acl::ADMIN, $this, 'exportMarkedOrders');
		$this->acl->allow(Acl::ADMIN, $this, 'exportOrdersData');
		$this->acl->allow(Acl::ADMIN, $this, 'exportProofreadersData');
		$this->acl->allow(Acl::ADMIN, $this, 'exportUsersData');
	}
	
	/**
	 * Changes the status of all new acc-recs to exporting
	 */
	public function markFinishedOrdersForExport()
	{
		$accRecs = $this->accRecRepo->getAccountsReceivable(AccountsReceivable::UNEXPORTED);
		foreach($accRecs as $accRec){
			$accRec->setState(AccountsReceivable::EXPORTING);
		}
	}
	
	/**
	 * Changes the status of a single acc-rec to exporting
	 * @param Order $order
	 */
	public function markForExport(Order $order)
	{
		$accRec = $this->accRecRepo->getSingleAccountReceivable($order);
		$accRec->setState(AccountsReceivable::EXPORTING);
	}

	/**
	 * Exports all marked accounts receivables to a csv file and sets their status to exported
	 */
	public function exportMarkedOrders()
	{
		$accRecs = $this->accRecRepo->getAccountsReceivable(AccountsReceivable::EXPORTING);
		
		$this->createCsv("AccRecs", $accRecs, $accRec);
		
			//To do: Return the document to the browser, ready for download and check whether it has worked
		
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
		
		$this->createCsv("Orders", $orders, $order);
		
		//To do: Return the document to the browser, ready for download and check whether it has worked
	}
	
	/**
	 * Export all data from the proofreaders
	 */
	public function exportProofreadersData()
	{
		$proofreaders = $this->proofreaderRepo->getAllProofreaders();
		
		$this->createCsv("Proofreaders", $proofreaders, $proofreader);
		
		//To do: Return the document to the browser, ready for download and check whether it has worked
	}
	
	/**
	 * Export all data from the users
	 */
	public function exportUsersData()
	{
		$users = $this->userRepo->getAllUsers();
		
		$this->createCsv("Users", $users, $user);
		
		//To do: Return the document to the browser, ready for download and check whether it has worked
	}
	
	/**
	 * Creates a csv file from an array
	 */
	public function createCsv($name, $array, $fields)
	{
		$fp = fopen($name . ".csv", 'w');
		foreach ($array as $fields) {
			fputcsv($fp, $fields);
		}
		
		fclose($fp);
	}
	
}