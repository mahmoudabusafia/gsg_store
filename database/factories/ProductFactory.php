<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $category = Category::inRandomOrder()
            ->limit(1)
            ->first(['id']); 

        $status = ['active', 'draft'];

        $name = $this->faker->name();
        
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'category_id' => $category? $category->id : null,  // if we use get insted of first ===> $category->count() > 0? $category->first()->id : null,   // $category[0]->id : null,
            'description' => $this->faker->words(200,true),
            'image_path' => $this->faker->imageUrl(),
            'status' => $status[rand(0,1)],
            'price' => $this->faker->randomFloat(2, 50, 2000),
            'quantity' => $this->faker->randomFloat(0, 0, 30),
        ];
    }
}
