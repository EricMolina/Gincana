<?php

namespace Database\Seeders;
use App\Models\Label;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Label::create([
            "name"=> "Restaurante",
            "color"=>"db4720",//Rojo anaranjado
            "img"=>"Restaurante.png",
        ]);
        Label::create([
            "name"=> "Monumento",
            "color"=>"90654b",//Marrón
            "img"=>"Monumento.png",
        ]);
        Label::create([
            "name"=> "Religioso",
            "color"=>"858585",//Gris
            "img"=>"Religioso.png",
        ]);
        Label::create([
            "name"=> "Parque",
            "color"=>"49b754",//Verde
            "img"=>"Parque.png",
        ]);
        Label::create([
            "name"=> "Museo",
            "color"=>"4975b7",//Azul
            "img"=>"Museo.png",
        ]);
        Label::create([
            "name"=> "Ocio",
            "color"=>"d83333",//Rojo
            "img"=>"Ocio.png",
        ]);
    }
}
