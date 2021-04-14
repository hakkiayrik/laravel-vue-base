<?php

namespace App\Models;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Log extends Model
{
	use SoftDeletes;
   	protected $fillable = [
	   	'user_id', 'action', 'action_model', 'action_id', 'old_data', 'new_data', 'ip'
   	];

   	public function user()
   	{
	   	return $this->belongsTo(Admin::class)->withTrashed();
   	}
}
