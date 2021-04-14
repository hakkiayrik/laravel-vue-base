<?php

namespace App\Helpers\Logger;

use App\Models\Log;

class DbLog implements LoggerInterface
{
	public function log($data)
	{
		Log::create($data);
	}
}