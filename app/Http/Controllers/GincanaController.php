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
        $gincanas = Gincana::withCount('gincana_points')->get();

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
}
