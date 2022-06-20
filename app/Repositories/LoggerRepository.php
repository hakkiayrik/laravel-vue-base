<?php
namespace App\Repositories;

use App\Interfaces\LoggerInterface;

class LoggerRepository
{
    private $loggerType;

    public function __construct(LoggerInterface $loggerType)
    {
        $this->loggerType = $loggerType;
    }

    public function initialize($value)
    {
        $this->loggerType->log($value);
    }
}
