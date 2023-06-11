<?php

namespace Database\Factories;

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
                return \App\Models\Category::factory()->create()->id;
            },
            'ingredients' => $this->faker->text,
            'special_requirements' => $this->faker->text,
            'recipe' => $this->faker->text,
            'restaurant_id' => function () {
                return \App\Models\Restaurant::factory()->create()->id;
            },
        ];
    }
}
