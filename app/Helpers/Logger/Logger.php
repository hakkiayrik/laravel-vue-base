<?php

namespace App\Helpers\Logger;

class Logger
{
	private $loggerType;

	public function __construct($loggerType)
	{
		$this->loggerType = $loggerType;
	}

	public function initialize($value)
	{
		$this->loggerType->log($value);
	}
}