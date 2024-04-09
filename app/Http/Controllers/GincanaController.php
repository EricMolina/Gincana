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
        try {
            DB::beginTransaction();

            $gincana = new Gincana;
            $gincana->name = $request->name;
            $gincana->desc = $request->desc;
            $gincana->difficulty = $request->difficulty;
            $gincana->coord_x = $request->coord_x;
            $gincana->coord_y = $request->coord_y;
            $gincana->user_id = Auth::user()->id;
            $gincana->save();

            $points = $request->points;
            foreach ($points as $point) {
                GincanaPoint::create([
                    'gincana_id' => $gincana->id,
                    'point_id' => $point['point_id'],
                    'order_id' => $point['order_id'],
                    'hint' => $point['hint'],
                ]);
            }

            DB::commit();

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
