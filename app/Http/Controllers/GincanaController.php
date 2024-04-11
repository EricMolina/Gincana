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


    function list_points(Request $request) {
        $labelFilters = json_decode($request->input('labelFilters'), true);
        $userLabelFilters = json_decode($request->input('userLabelFilters'), true);
    
        $query = Point::with('labels', 'main_label', 'user_labels');
    
        if (in_array(1, $labelFilters)) {
            foreach ($labelFilters as $id => $value) {
                if ($value == 1) {
                    $query->whereHas('labels', function ($query) use ($id) {
                        $query->where('label_id', $id);
                    });
                }
            }
        }
    
        if (in_array(1, $userLabelFilters)) {
            foreach ($userLabelFilters as $id => $value) {
                if ($value == 1) {
                    $query->whereHas('user_labels', function ($query) use ($id) {
                        $query->where('user_label_id', $id);
                    });
                }
            }
        }
    
        return $query->get();
    }

    function list_points_search(Request $request) {
        $searchVal = $request->input('search');
    
        $query = Point::with('labels');
    
        if ($searchVal != '') {
            $query->where('name', 'like', '%' . $searchVal . '%')
                  ->orWhere('address', 'like', '%' . $searchVal . '%')
                  ->orWhere('desc', 'like', '%' . $searchVal . '%');
        }
    
        return $query->get();
    }
    
    function create(){
        return view("gincana.create");
    }
}
