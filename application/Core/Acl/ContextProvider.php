<?php

namespace Core\Acl;


class ContextProvider
{
	
	/**
	 * @var Core\Acl\ContextStorage
	 * @Inject Core\Acl\ContextStorage
	 */
	protected $contextStorage;
	
	
	/**
	 * @return Core\Acl\Context
	 */
	public function getContext()
	{
		return $this->contextStorage->getContext();
	}
	
}