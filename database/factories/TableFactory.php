<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Models\Table>
 */
class TableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number' => $this->faker->unique()->numberBetween(1, 20),
            'capacity' => $this->faker->numberBetween(2, 8),
            'status' => $this->faker->randomElement(['reserved', 'free']),
            'restaurant_id' => function () {
                return \App\Models\Restaurant::factory()->create()->id;
            },
        ];
    }
}
