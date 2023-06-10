<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Dish;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\Restaurant;
use App\Models\Table;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'John Shuts',
            'email' => 'admin@admin.com',
            'role' => 'admin',
            'password' => 'password'
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Max Kostenko',
            'email' => 'koctenko525@gmail.com',
            'role' => 'client',
            'password' => 'password'
        ]);

        Table::factory()->times(3)->create();

        Dish::factory()->count(10)->create();

        Reservation::factory()->count(5)->create();
    }
}
