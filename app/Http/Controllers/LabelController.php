<?php

namespace App\Http\Controllers;
use App\Models\Label;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    public function store(Request $request){
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
        $id = $request->input("id");
        $label = Label::find($id);
        if(!$request->input("name")){
            return 'El campo nombre no puede estar vacío';
        }
        if($label->name != $request->input('name')){
            $label = Label::where('name',$label->name)->first();
            if($label){
                return 'El nombre de la categoría ya existe';
            }
        }
        try {
            $label->name = $request->name;
            // $color = str_replace("#","",$request->input("labelColor"));
            $label->color = str_replace("#","",$request->input("labelColor"));
            $label->save();
            return "ok";
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
