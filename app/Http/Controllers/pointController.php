<?php

namespace App\Http\Controllers;
use App\Models\Point;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
class pointController extends Controller
{
    public function index(){
        return view("admin.point.index");
    }
    public function list(Request $request){
        $src = $request->input("name");
        if($src == ""){
            $point = Point::all();
        }else{
            $point = Point::where("name","LIKE",$src."%")->get();
        }
        return response()->json($point);
    }
    public function update(Request $request){
        // $file = $request->file('img');
        $id = $request->input("id");
        $point = Point::find($id);
        try {
            $point->name = $request->name;
            $point->address = $request->address;
            $point->coord_x = $request->coordx;
            $point->coord_y = $request->coordy;
            $point->save();
            return "ok";
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function store(Request $request){
        try {
            $point = new Point();
            $point->name = $request->name;
            $point->address = $request->address;
            $point->coord_x = $request->coordx;
            $point->coord_y = $request->coordy;
            $point->save();
            return "ok";
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }
    public function show(Request $request){
        $id = $request->input("id");
        $point = Point::find($id);
        return response()->json($point);
    }
    public function delete(Request $request){
        $id = $request->input("id");
        try {
            $point = Point::find($id);
            $point->delete();
            return "ok";
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
