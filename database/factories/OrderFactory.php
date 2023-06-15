<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'status' => $this->faker->randomElement(['pending', 'completed', 'cancelled']),
            'user_id' => function () {
                return \App\Models\User::factory()->create()->id;
            },
            'table_id' => function () {
                return \App\Models\Table::factory()->create()->id;
            },
        ];
    }
}
