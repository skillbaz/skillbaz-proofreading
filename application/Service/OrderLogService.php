<?php

namespace Service;

use Entity\OrderLog;

use Core\Service\ServiceBase;


class OrderLogService extends ServiceBase
{
	public function createLog($logType, $comment, $order)
	{
		$orderLog = new OrderLog($logType, $comment, $order);
		$this->persist($orderLog);
	}
}