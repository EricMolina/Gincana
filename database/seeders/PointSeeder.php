<?php

namespace Database\Seeders;
use App\Models\Point;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        Point::create([
            'coord_x' => 41.35057227833654,
            'coord_y' => 2.106786064542424,
            'name' => 'Point 1',
            'img' => null
        ]);

        Point::create([
            'coord_x' => 41.368057227833654,
            'coord_y' => 2.106786064542424,
            'name' => 'Point 2',
            'img' => null
        ]);

        Point::create([
            'coord_x' => 41.37057227833654,
            'coord_y' => 2.106786064542424,
            'name' => 'Point 3',
            'img' => null
        ]);
    }
}
