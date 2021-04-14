<?php

namespace Database\Seeders;

use App\Models\Avatar;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class AvatarsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		//Get avatar file names
		$directory = public_path('assets/images/avatars');
		$files = File::files($directory);

		foreach ($files as $file) {
			$file = pathinfo($file);
			Avatar::create(['image_path' => $file["basename"]]);
		}
    }
}
