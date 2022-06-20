<?php

namespace Database\Seeders;

use App\Models\UserLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		//Create Fake Users
		User::factory()->count(10)->create()->each(function ($user) {
			//create user log
			$logData["user_id"] = $user->id;
			$logData["type"] = "create";
			$logData["message_lang_code"] = "user_create_message";
			$logData["data"] = json_encode($user->toArray());
			$logData["created_by"] = $user->id;

			UserLog::create($logData);
		});
    }
}
