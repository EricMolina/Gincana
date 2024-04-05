<?php

namespace App\Http\Controllers;
use App\Models\Point;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
class pointController extends Controller
{
    public function index(){
        return view("admin.point.index");
    }
    public function list(Request $request){
        $src = $request->input("name");
        if($src == ""){
            $point = Point::with("main_label")->with("labels")->get();
        }else{
            $point = Point::where("name","LIKE",$src."%")->get();
        }
        return response()->json($point);
    }
    public function update(Request $request){
        $id = $request->input("id");
        $point = Point::find($id);
        $imgOld = $point->img;
        $img = $request->file("img");
        $filename = time().'.'.$img->getClientOriginalExtension();
        $img->move(public_path('img/points'), $filename);
        try {
            $point->img = $filename;
            $point->name = $request->name;
            $point->address = $request->address;
            $point->coord_x = $request->coordx;
            $point->coord_y = $request->coordy;
            $point->save();
            // Storage::delete("img/points/$imgOld");
            if(File::exists("img/points/$imgOld")){
                File::delete("img/points/$imgOld");
            }
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
            $filename = time().'.'.$request->file("img")->getClientOriginalExtension();
            $point->img = $filename;
            $point->save();
            $request->file("img")->move(public_path('img/points'), $filename);
            return "ok";
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }
    public function show(Request $request){
        $id = $request->input("id");
        $point = Point::with("main_label")->with("labels")->get()->find($id);
        return response()->json($point);
    }
    public function delete(Request $request){
        $id = $request->input("id");
        try {
            $point = Point::find($id);
            $point->delete();
            $imgName = $point->img;
            if(File::exists("img/points/$imgName")){
                File::delete("img/points/$imgName");
            }
            return "ok";
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
