<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => function () {
                return \App\Models\User::factory()->create()->id;
            },
            'restaurant_id' => function () {
                return \App\Models\Restaurant::factory()->create()->id;
            },
            'table_id' => function () {
                return \App\Models\Table::factory()->create()->id;
            },
            'reservation_date' => $this->faker->dateTimeBetween('now', '+7 days'),
        ];
    }
}
