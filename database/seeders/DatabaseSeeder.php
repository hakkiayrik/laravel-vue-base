<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
		$this->call(AvatarsTableSeeder::class);
		$this->call(LanguagesTableSeeder::class);
		$this->call(RolesPermissionsSeeder::class);
		$this->call(AdminsTableSeeder::class);
		$this->call(UsersTableSeeder::class);
		$this->call(CategoriesSeeder::class);
    }
}
