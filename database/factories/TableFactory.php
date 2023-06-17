<?php

namespace Database\Factories;

use App\Models\Restaurant;
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
            'status' => 'free',
            'restaurant_id' => function () {
                return Restaurant::factory()->create()->id;
            },
        ];
    }
}
