<?php

namespace Acl;


class ContextProvider
{
	
	/**
	 * @var Acl\ContextStorage
	 * @Inject Acl\ContextStorage
	 */
	protected $contextStorage;
	
	
	/**
	 * @return Acl\Context
	 */
	public function getContext()
	{
		return $this->contextStorage->getContext();
	}
	
}