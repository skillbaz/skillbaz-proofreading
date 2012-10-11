<?php

namespace Service;


use Acl\Acl;

use Entity\User;
use Entity\Address;

use Core\Service\ServiceBase;


class DocumentService
	extends ServiceBase
{
	
	public function _setupAcl(){
		
	}
	
	
	/**
	 * Returns an Document, if the authenticated User
	 * is allowed to see this Document
	 * 
	 * @param string $documentId
	 * @return Entity\Document
	 */
	public function getDocument($documentId){
		// ToDo
	}
	
	
	/**
	 * Creates a new Document and returns it.
	 * 
	 * @param Params $params
	 * @return Entity\Document
	 */
	public function createDocument(Params $params){
		// ToDo
	}
	
	/**
	 * Counts the words in the document and returns it.
	 * 
	 * @param Document $document
	 * @return integer
	 */
	public function countWords($document){
		return 34;
	}
	
}