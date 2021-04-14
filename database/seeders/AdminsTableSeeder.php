<?php
namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$adminData = [
			'first_name' => 'Hakkı',
			'last_name' => 'Ayrık',
			'username' => 'admin',
			'email' => 'hakkiayrik94@gmail.com',
			'password' => bcrypt('123123'),
			'avatar_id' => 1
		];

		$admin = Admin::create($adminData);

		//Role Assign
		$admin->assignRole('Admin');
    }
}
