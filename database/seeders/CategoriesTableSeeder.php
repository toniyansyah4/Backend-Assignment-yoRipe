<?php

namespace Database\Seeders;

use App\Modules\Category\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = ['Hotel', 'Food', 'Healthy'];

        foreach ($categories as $category) {
            Category::create([
                'category' => $category,
                'slug' => Str::slug($category, '-'),
            ]);
        }
    }
}
