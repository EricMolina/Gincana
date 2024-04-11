<?php

namespace App\Http\Controllers;
use App\Models\UserLabel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class UserLabelController extends Controller
{
    public function store(Request $request) {
        $userLabel = new UserLabel();
        $userLabel->name = $request->input("name");
        $userLabel->user_id = $request->input("user_id");
        $userLabel->save();
        return "ok";
    }

    public function data(Request $request) {
        $userLabels = UserLabel::where("user_id", $request->id)->get();
        return response()->json($userLabels);        
    }

    public function store_point (Request $request) {
        $label_id = $request->input("label_id");
        $point_id = $request->input("point_id");
    
        $exists = DB::table('user_labels_points')
            ->where('user_label_id', $label_id)
            ->where('point_id', $point_id)
            ->exists();
    
        if ($exists) {
            return "duplicated";
        }
    
        DB::table('user_labels_points')->insert([
            'user_label_id' => $label_id,
            'point_id' => $point_id
        ]);
    
        return "ok";
    }

    public function delete(Request $request) {
        $label_id = $request->input("label_id");
        DB::table('user_labels_points')
            ->where('user_label_id', $label_id)
            ->delete();
        DB::table('user_labels')
            ->where('id', $label_id)
            ->delete();
        return "ok";
    }

    public function delete_point (Request $request) {
        $label_id = $request->input("label_id");
        $point_id = $request->input("point_id");
    
        DB::table('user_labels_points')
            ->where('user_label_id', $label_id)
            ->where('point_id', $point_id)
            ->delete();
        return "ok";
    }
}
