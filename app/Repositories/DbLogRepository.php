<?php
namespace App\Repositories;

use App\Models\Log;
use App\Interfaces\LoggerInterface;

class DbLogRepository implements LoggerInterface
{
    public function log($data)
    {
        Log::create($data);
    }
}
