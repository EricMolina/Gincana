<?php

namespace App\Http\Controllers;
use App\Models\Point;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\GincanaSessionGroupUserCheckpoint;
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
        if(!isset($request->lab)){
            return "Indica almenos una categoría";
        }
        $lab =  $request->lab;
        $id = $request->input("id");
        $point = Point::with("labels")->get()->find($id);
        if($request->name == "" || $request->name == null){
            return "El campo nombre es obligatorio";
        }
        if($request->desc == "" || $request->desc == null){
            return "El campo descripción es obligatorio";
        }
        if($request->address == "" || $request->address == null){
            return "El campo dirección es obligatorio";
        }
        if($request->coordx == "" || $request->coordx == null){
            return "Falta la ubicación en el mapa";
        }
        if($request->coordy == "" || $request->coordy == null){
            return "Falta la ubicación en el mapa";
        }
        try {
            DB::beginTransaction();
            foreach ($point["labels"] as $label) {
                $label["pivot"]->delete();
            }
            foreach ($lab as $label) {
                $point->labels()->attach($label);
            }
            $img = $request->file("img");
            $imgOld = $point->img;
            if($img != NULL){
                $filename = time().'.'.$img->getClientOriginalExtension();
                $img->move(public_path('img/points'), $filename);
                $point->img = $filename;
                if(File::exists("img/points/$imgOld")){
                    File::delete("img/points/$imgOld");
                }
            }
            $point->main_label_id = $request->labelMain;
            $point->name = $request->name;
            $point->desc = $request->desc;
            $point->address = $request->address;
            $point->coord_x = $request->coordx;
            $point->coord_y = $request->coordy;
            $point->save();
            DB::commit();
            return "ok";
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
    public function store(Request $request){
        if(!isset($request->lab)){
            return "Indica almenos una categoría";
        }
        if(!isset($request->img)){
            return "Falta una imagen";
        }
        $lab =  $request->lab;
        // return $request;
        if($request->name == "" || $request->name == null){
            return "El campo nombre es obligatorio";
        }
        if($request->address == "" || $request->address == null){
            return "El campo dirección es obligatorio";
        }
        if($request->desc == "" || $request->desc == null){
            return "El campo descripción es obligatorio";
        }
        
        if($request->coordx == "" || $request->coordx == null){
            return "Falta la ubicación en el mapa";
        }
        if($request->coordy == "" || $request->coordy == null){
            return "Falta la ubicación en el mapa";
        }
        // return $lab;
        // if(count($lab) == 0){
        //     return "Define almenos un punto";
        // }
        try {
            DB::beginTransaction();
            $point = new Point();
            $point->main_label_id = $request->labelMain;
            $point->name = $request->name;
            $point->address = $request->address;
            $point->coord_x = $request->coordx;
            $point->coord_y = $request->coordy;
            $filename = time().'.'.$request->file("img")->getClientOriginalExtension();
            $point->img = $filename;
            $point->desc = $request->desc;
            $point->save();
            $request->file("img")->move(public_path('img/points'), $filename);
            foreach ($lab as $label) {
                // $label->points()->attach($label);
                $point->labels()->attach($label);
            }
            DB::commit();
            return "ok";
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
    public function show(Request $request){
        $id = $request->input("id");
        $point = Point::with("main_label")->with("labels")->get()->find($id);
        return response()->json($point);
    }
    public function delete(Request $request){
        DB::beginTransaction();
        $id = $request->input("id");
        try {
            $point = Point::with("main_label")->with("user_labels")->with("gincana_points")->with("labels")->get()->find($id);
            foreach ($point["gincana_points"] as $gincana_points) {
                // Primero eliminamos los registros en gin_ses_grp_usr_checkpoints
                GincanaSessionGroupUserCheckpoint::where("gincana_point_id",$gincana_points["point_id"])->delete();
                // Luego eliminamos el registro en gincana_points
                $gincana_points->delete();
            }
    
            foreach ($point["user_labels"] as $labels) {
                $labels["pivot"]->delete();
                $labels->delete();
            }
            foreach ($point["labels"] as $label) {
                $label["pivot"]->delete();
            }
            $point->delete();
            $imgName = $point->img;
            if(File::exists("img/points/$imgName")){
                File::delete("img/points/$imgName");
            }
            DB::commit();
            return "ok";
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
