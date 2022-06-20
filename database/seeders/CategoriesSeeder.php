<?php

namespace Database\Seeders;

use App\Models\Category;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{

    use Translatable;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Create Fake Category

        Category::factory()->count(10)->create();
    }
}
