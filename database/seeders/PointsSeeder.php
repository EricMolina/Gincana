<?php

namespace Database\Seeders;
use App\Models\Point;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PointsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Point::create([
        //     "coord_x"=>"",
        //     "coord_y"=> "",
        //     "name"=>"",
        //     "main_label_id"=>"6",
        //     "address"=> "",
        //     "img"=>"",
        // ]);
        Point::create([
            "coord_x"=>"41.3499005",
            "coord_y"=> "2.1047223",
            "name"=>"Joan23",
            "main_label_id"=>"6",
            "address"=> "Av. Mare de Déu de Bellvitge, 100, 110, 08907 L'Hospitalet de Llobregat, Barcelona",
            "img"=>"cole.jpg",
        ]);
    }
}
