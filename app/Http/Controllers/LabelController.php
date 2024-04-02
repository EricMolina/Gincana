<?php

namespace App\Http\Controllers;
use App\Models\Label;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    public function index(){
        return view("label.index");
    }
    public function list(Request $request){
        $src=$request->src;
        if($src == ""){
            $labels = Label::all();
        }else{
            $labels = Label::where("name","LIKE","%".$src."%")->get();
        }
        return response()->json($labels);
    }
    public function create(){
        return view("label.create");
    }
    public function store(Request $request){
        $validated = $request->validate([
            "name"=> "required|unique:labels|max:255",
            "labelColor"=> "required"
        ]);

        $label = new Label();
        $label->name=$request->input("name");
        $label->color = $request->input("labelColor");
        $label->save();
        return redirect()->route("label.index");
    }
}
