<?php

namespace App\Http\Controllers;

use App\Models\Gincana;
use App\Models\GincanaPoint;
use App\Models\Point;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;


class GincanaController extends Controller
{
    function store(Request $request) {
        if(!isset($request->name)){
            return "Es obligatorio indicar un nombre";
        }
        if(!isset($request->desc)){
            return "Es obligatorio indicar una descripción";
        }
        if(!isset($request->difficulty)){
            return "Es obligatorio indicar una descripción";
        }
        if(!is_numeric($request->difficulty)){
            return "La dificultad debe de ser numerico";
        }
        if(!isset($request->coordx) || !isset($request->coordy)){
            return "Faltan la ubicación del punto inicial";
        }
        $points = $request->points;
        $hints = $request->hints;
        if(!isset($points)){
            return "Tienes que definir almenos un punto";
        }
        for ($i = 0; $i < count($points); $i++) {
            if($hints[$i]=="" || $hints == null){
                $punto = $i+1;
                return "Falta definir la pista del punto $punto";
            }
        }

        try {
            DB::beginTransaction();

            $gincana = new Gincana;
            $gincana->name = $request->name;
            $gincana->desc = $request->desc;
            $gincana->difficulty = $request->difficulty;
            $gincana->coord_x = $request->coordx;
            $gincana->coord_y = $request->coordy;
            $gincana->user_id = Auth::user()->id;
            $gincana->save();
            for ($i = 0; $i < count($points); $i++) {
                $order = $i+1;
                GincanaPoint::create([
                    'gincana_id' => $gincana->id,
                    'point_id' => $points[$i],
                    'order_id' => $order,
                    'hint' => $hints[$i],
                ]);
            }
            DB::commit();
            return "ok";
        } catch (Exception $e) {
            DB::rollBack();
            return "error: ".$e->getMessage();
        }
    }


    function list() {
        $gincanas = Gincana::withCount(
            'gincana_points'
        )->with(
            'gincana_creator'
        )->get();

        return $gincanas->map(function ($gincana) {
            $gincana->is_owner = Auth::user()->id == $gincana->user_id;
            return $gincana;
        });
    }
    function create(){
        return view("gincana.create");
    }
    function list_points() {
        return Point::all();
    }
}
