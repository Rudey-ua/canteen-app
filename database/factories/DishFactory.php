<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dish>
 */
class DishFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(2),
            'description' => $this->faker->paragraph(2),
            'price' => $this->faker->randomFloat(2, 5, 50),
            'category_id' => function () {
                return Category::factory()->create()->id;
            },
            'ingredients' => $this->faker->sentence(4),
            'recipe' => $this->faker->sentence(6),
            'restaurant_id' => function () {
                return Restaurant::factory()->create()->id;
            },
        ];
    }
}
