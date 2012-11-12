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
		$accRecs = $this->accRecRepo->findByState(AccountsReceivable::UNEXPORTED);
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
		$accRec = $this->accRecRepo->findByOrder($order);
		$accRec->setState(AccountsReceivable::EXPORTING);
	}

	/**
	 * Exports all marked accounts receivables to a csv file and sets their status to exported
	 */
	public function exportMarkedOrders()
	{
		$accRecs = $this->accRecRepo->findByState(AccountsReceivable::EXPORTING);
		
		$fp = fopen("accRecs.csv", 'w');
		foreach ($accRecs as $row) {
			$row = array(
					"order_id" => $row->getOrder()->getId(),
					"settled_price" => $row->getSettledPrice(),
					"settled_salary" => $row->getProofreaderSalarySettled());
			
			fputcsv($fp, $row);
		}
	
		fclose($fp);
		
			//To do: Check whether it has worked
		
		foreach($accRecs as $accRec){
			$accRec->setState(AccountsReceivable::EXPORTED);
		}
	}
	
	/**
	 * Export all orders
	 */
	public function exportOrdersData()
	{
		$orders = $this->orderRepo->findAllOrders();
		
		$fp = fopen("Orders.csv", 'w');
		foreach ($orders as $row) {
			$row = array(
					"order_id" => $row->getId(),
					"state" => $row->getState(),
					"iteration" => $row->getIteration(),
					"offered_price" => $row->getOfferedPrice(),
					"settled_price" => $row->getSettledPrice(),
					"offered_salary" => $row->getProofreaderSalaryOffered(),
					"settled_salary" => $row->getProofreaderSalarySettled(),
					"deadline" => $row->getDeadline(),
					"description" => $row->getDescription(),
					"public_comment" => $row->getPublicComment(),
					"internal_comment" => $row->getInternalComment(),
					"pricing_scheme" => $row->getPricing()->getName(),
					"user_id" => $row->getUser()->getId(),
					"order_street" => $row->getAddress()->getStreet(),
					"order_zipcode" => $row->getAddress()->getZipcode(),
					"order_city" => $row->getAddress()->getCity(),
					"order_country" => $row->getAddress()->getCountry(),
					"proofreader_id" => $row->getProofreader()->getId(),
					"field" => $row->getField(),
					"rating" => $row->getRating()->getGrade());
			
			fputcsv($fp, $row);
		}
	
		fclose($fp);
		
		//To do: Check whether it has worked
	}
	
	/**
	 * Export all data from the proofreaders
	 */
	public function exportProofreadersData()
	{
		$proofreaders = $this->proofreaderRepo->findAllProofreaders();
		
		$fp = fopen("Proofreaders.csv", 'w');
		foreach ($proofreaders as $row) {
			$row = array(
					"proofreader_id" => $row->getId(),
					"active" => $row->getActive(),
					"user_id" => $row->getUser()->getId());
						
						// To do: Get Abilities into seperate fields
						// To do: Calculate the mean of the ratings and save it
			
			fputcsv($fp, $row);
		}
	
		fclose($fp);
		
		//To do: Check whether it has worked
	}
	
	/**
	 * Export all data from the users
	 */
	public function exportUsersData()
	{
		$users = $this->userRepo->findAllUsers();
		
		$fp = fopen("Users.csv", 'w');
		foreach ($users as $row) {
			$row = array(
					"user_id" => $row->getId(),
					"active" => $row->getActive(),
					"firstname" => $row->getFirstname(),
					"surname" => $row->getSurname(),
					"email" => $row->getEmail(),
					"skype" => $row->getSkype(),
					"language" => $row->getPrefLanguage(),
					"student" => $row->isStudent(),
					"admin" => $row->isAdmin(),
					"user_street" => $row->getAddress()->getStreet(),
					"user_zipcode" => $row->getAddress()->getZipcode(),
					"user_city" => $row->getAddress()->getCity(),
					"user_country" => $row->getAddress()->getCountry(),
					"proofreader" => $row->getProofreader()->getId());
			
			fputcsv($fp, $row);
		}
	
		fclose($fp);
		
		//To do: Check whether it has worked
	}
	
}