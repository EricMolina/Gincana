<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\PointSeeder;

use Database\Seeders\LabelSeeder;
use Database\Seeders\PointsSeeder;
use Database\Seeders\userSeeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(LabelSeeder::class);
        $this->Call(PointSeeder::class);
        $this->Call(userSeeder::class);
    }
}
