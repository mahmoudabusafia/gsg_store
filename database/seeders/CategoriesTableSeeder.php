<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ORM Eloquent Model
        Category::create([
            'name' => 'Category Model',
            'slug' => 'category-model',
            'status' => 'draft',
        ]);

        // Query Builder
        for($i = 1; $i <= 10; $i++){
        DB::table('categories')->insert([
            'name' => 'Category ' . $i,
            'slug' => 'category-' . $i,
            'status' => 'active',
        ]);
        }
        
        // SQL commands 
        DB::statement("INSERT INTO categories (name, slug, status)
        Values ('My First Category', 'my-first-category', 'active')");
    }
}
