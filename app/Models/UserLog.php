<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
	protected $fillable = [
		'user_id', 'type', 'message_lang_code', 'created_by', 'data', 'old_data'
	];

}
