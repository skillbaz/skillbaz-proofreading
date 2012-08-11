<?php

namespace Core\Service;

use Core\Service\Params;


class FormParams extends Params{
	
	private $form;
	
	public function __construct(\Zend_Form $form){
		$this->form = $form;
	}
	
	public function getValue($name){
		return $this->form->getElement($name)->getValue($name);
	}
	
	public function setValue($name, $value){
		$this->form->getElement($name)->setValue($value);
	}
	
	public function hasElement($name){
		return $this->form->getElement($name) != null;
	}
	
	
	
	public function addMessage($name, $message){
		$this->form->getElement($name)->addErrorMessage($message);
	}
	
	public function addMessages($name, array $messages){
		$this->form->getElement($name)->addErrorMessages($messages);
	}
	
	public function setMessages($name, array $messages){
		$this->form->getElement($name)->setErrorMessages($messages);
	}
	
	public function getMessages($name){
		$this->form->getElement($name)->getErrorMessages();
	}
	
	public  function clearMessages($name){
		$this->form->getElement($name)->clearErrorMessages();
	}
	
	
	
	public function addError($name, $message){
		$this->form->getElement($name)->addError($message);
	}
	
	public function addErrors($name, array $messages){
		$this->form->getElement($name)->addErrors($messages);
	}
	
	public function setErrors($name, array $messages){
		$this->form->getElement($name)->setErrors($messages);
	}
	
	public function getErrors($name){
		$this->form->getElement($name)->getErrors();
	}
	
	public function clearErrors($name){
		$this->form->getElement($name)->clearErrorMessages();
	}
	
} 
