<?php

namespace Database\Factories;

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
    public function definition(): array
    {
        $title = fake()->unique()->words(rand(1,3), true);
        $slug = Str::slug($title);
        
        
        return [
            'title' => $title,
            'slug' => $slug,
            'SKU' => fake()->unique()->ean13(),
            'description' => fake()->optional()->sentences(rand(1, 3), true),
            'price' => fake()->randomFloat(2, 10, 200),
            'discount' => fake()->optional()->numberBetween(10, 85),
            'quantity' => fake()->numberBetween(0, 50),
            'thumbnail' => 'test',
        ];
    }
}
