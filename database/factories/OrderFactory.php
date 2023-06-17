<?php

namespace Database\Factories;

use App\Models\Table;
use App\Models\User;
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
        $statuses = ['ordered', 'preparing', 'ready', 'served', 'paid'];

        return [
            'order_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'status' => $this->faker->randomElement($statuses),
            'user_id' => function () {
                return User::factory()->create()->id;
            },
            'table_id' => function () {
                return Table::factory()->create()->id;
            },
        ];
    }
}
