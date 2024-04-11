<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Database\Seeders\LabelSeeder;
use Database\Seeders\PointSeeder;
use Database\Seeders\UserSeeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(LabelSeeder::class);
        $this->Call(PointSeeder::class);
        $this->Call(UserSeeder::class);
    }
}
