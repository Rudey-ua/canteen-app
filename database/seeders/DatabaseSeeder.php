<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        \App\Models\User::factory()->create([
            'name' => 'Duo Megan',
            'email' => 'duomegan@gmail.com',
            'role' => 'waiter',
            'password' => 'password'
        ]);

        $restaurant = Restaurant::factory()->create([
            'name' => 'Hehuty',
            'address' => 'вулиця Миколи Закревського, 20',
            'contact_info' => '068 861 3381',
            'working_hours' => '11:00 - 23:00'
        ]);

        Table::factory()->times(3)->create();

        Table::factory()->create([
            'number' => '1',
            'capacity' => '4',
            'status' => 'free',
            'restaurant_id' => $restaurant->id
        ]);

        Table::factory()->create([
            'number' => '2',
            'capacity' => '2',
            'status' => 'reserved',
            'restaurant_id' => $restaurant->id
        ]);
    }
}
