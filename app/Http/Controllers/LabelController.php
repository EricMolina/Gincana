<?php

namespace App\Http\Controllers;
use App\Models\Label;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
class LabelController extends Controller
{
    public function index(){
        return view("admin.label.index");
    }
    public function list(Request $request){
        $src=$request->src;
        if($src == ""){
            $labels = Label::all();
        }else{
            $labels = Label::where("name","LIKE",$src."%")->get();
        }
        return response()->json($labels);
    }
    public function getlabel(){
        $labels = Label::all();
        return response()->json($labels);
    }
    public function store(Request $request){
        $img = $request->file("img");
        if($img == NULL){
            return "Debes añadir una imagen al icono";
        }
        // return $img->getClientOriginalExtension();
        if($img->getClientOriginalExtension() != "png"){
            if($img->getClientOriginalExtension() != "jpg"){
                return "Formato no permitido, el fichero debe ser .png o .jpg";
            }
        }
        
        if(!$request->input("name")){
            return 'El campo nombre no puede estar vacío';
        }
        $label = Label::where('name',$request->input("name"))->first();
        if($label){
            return 'El nombre de la categoría ya existe';
        }
        $color = str_replace("#","",$request->input("labelColor"));
        $label = new Label();
        $label->name=$request->input("name");
        if(!$color){
            $label->color ="000000";
        }
        $label->color = $color;
        $filename = $label->name.'.'.$img->getClientOriginalExtension();
        $img->move(public_path('img/labels'), $filename);
        $label->img = $filename;
        $label->save();
        return "ok";
    }
    public function delete(Request $request){
        // return $point_label["points"][0]["id"];
        $id = $request->input("id");
        $point_label= Label::with("points")->find($id);
        // return $point_label["points"][0];
        try {
            DB::beginTransaction();
            foreach ($point_label["points"] as $labels) {
                $labels["pivot"]->delete();
            }
            $point_label->delete();
            DB::commit();
            if(File::exists("img/labels/$point_label->img")){
                File::delete("img/labels/$point_label->img");
            }
            echo "ok";
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
        }
    }
    public function show(Request $request){
        $id = $request->input("id");
        $label= Label::find($id);
        return response()->json($label);
    }
    public function update(Request $request){
        $img = $request->file("img");
        $id = $request->input("id");
        $label = Label::find($id);
        if(!$request->input("name")){
            return 'El campo nombre no puede estar vacío';
        }
        if($img != NULL){
            if($img->getClientOriginalExtension() != "png"){
                if($img->getClientOriginalExtension() != "jpg"){
                    return "Formato no permitido, el fichero debe ser .png o .jpg";
                }
            }
        }
        if($label->name != $request->input('name')){
            $labelVal = Label::where('name',$request->input('name'))->get();
            // return count($label);
            if(count($labelVal) != 0){
                return 'El nombre de la categoría ya existe';
            }
        }
        try {
            $label->name = $request->name;
            // $color = str_replace("#","",$request->input("labelColor"));
            $label->color = str_replace("#","",$request->input("labelColor"));
            if($img != NULL){
                $filename = $label->name.'.'.$img->getClientOriginalExtension();
                if(File::exists("img/labels/$label->img")){
                    File::delete("img/labels/$label->img");
                }
                $img->move(public_path('img/labels'), $filename);
                $label->img = $filename;
            }
            $label->save();
            return "ok";
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
