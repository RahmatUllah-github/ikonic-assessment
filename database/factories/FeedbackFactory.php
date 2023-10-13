<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Feedback>
 */
class FeedbackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => fake()->numberBetween(1, Category::$FACTORY_RECORDS_COUNT),
            'user_id' => fake()->numberBetween(1, User::FACTORY_RECORDS_COUNT),
            'product_id' => fake()->numberBetween(1, Product::FACTORY_RECORDS_COUNT),
            'title' => fake()->sentence(4),
            'description' => fake()->sentence(10),
        ];
    }
}
