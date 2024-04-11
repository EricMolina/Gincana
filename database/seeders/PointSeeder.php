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
            "coord_x"=>"41.35009080157191",
            "coord_y"=> "2.1078886027912436",
            "name"=>"Joan23",
            "main_label_id"=>"2",
            "address"=> "Av. Mare de Déu, 100",
            "desc"=>"Desde el jardín de infancia hasta la escuela secundaria, incluye cursos profesionales.",
            "img"=>"cole.jpg",
        ]);
        $point = Point::with("main_label")->with("labels")->find(1);
        $point->labels()->attach(2);
        $point->labels()->attach(3);
        Point::create([
            "coord_x"=>"41.35337989273277",
            "coord_y"=> "2.109998190149765",
            "name"=>"Bar Paco",
            "main_label_id"=>"1",
            "address"=> "Rambla de la Marina, 362",
            "desc"=>"Si te apetece comer la comida que comerías a diario en casa, pero de una manera rápida y cómoda, Bar Paco.",
            "img"=>"barpaco.jpg",
        ]);
        $point = Point::with("main_label")->with("labels")->find(2);
        $point->labels()->attach(1);
        $point->labels()->attach(6);

        Point::create([
            "coord_x"=>"41.36447316546482",
            "coord_y"=> "2.0963244948732744",
            "name"=>"Parque de Can Buxeres",
            "main_label_id"=>"4",
            "address"=> "Ctra. Esplugues, 1",
            "desc"=>"Parque con una casa señorial de 1911, un vivero, un jardín y esculturas de Héctor Cesena y Rafael Solanic.",
            "img"=>"canbuxeres.jpg",
        ]);
        $point = Point::with("main_label")->with("labels")->find(3);
        $point->labels()->attach(4);

        Point::create([
            "coord_x"=>"41.34785406067367",
            "coord_y"=> "2.108819023568787",
            "name"=>"Plató de rodaje epico",
            "main_label_id"=>"6",
            "address"=> "C. de la Ermita de Bellvitge, 15",
            "desc"=>"Aquí se rodó una de las escenas mas importantes del cine español.",
            "img"=>"jordi.jpg",
        ]);
        $point = Point::with("main_label")->with("labels")->find(4);
        $point->labels()->attach(6);
        $point->labels()->attach(2);


        Point::create([
            "coord_x"=>"41.350671687019485",
            "coord_y"=> "2.1125866123556984",
            "name"=>"Cataluña Pita House",
            "main_label_id"=>"1",
            "address"=> "Rambla de la Marina, 80, 08907 L'Hospitalet de Llobregat, Barcelona",
            "desc"=>"El restaurante kebab de la plaza. Todo correcto y raciones muy grandes como para repetir varias veces.",
            "img"=>"pitahouse.jpg",
        ]);
        $point = Point::with("main_label")->with("labels")->find(5);
        $point->labels()->attach(1);
        $point->labels()->attach(6);
    }
}