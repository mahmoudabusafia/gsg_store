<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // Faker

        // SELSCT * FROM categories ORDER BY RSND() LIMIT 1   // get alawys return a collection
        // Collection 
        $category = DB::table('categories')
            ->inRandomOrder()
            ->limit(1)
            ->first(['id']); 

        $status = ['active', 'draft'];

        $name = $this->faker->name();
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'parent_id' => $category? $category->id : null,  // if we use get insted of first ===> $category->count() > 0? $category->first()->id : null,   // $category[0]->id : null,
            'description' => $this->faker->words(200,true),
            'image_path' => $this->faker->imageUrl(),
            'status' => $status[rand(0,1)],
        ];
    }
}
