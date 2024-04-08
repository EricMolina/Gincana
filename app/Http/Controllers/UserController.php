<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        return view("admin.user.index");
    }
    public function list(Request $request){
        // return "hola";
        $src=$request->src;
        if($src == ""){
            $users = User::all();
        }else{
            $users = User::where("name","LIKE",$src."%")->get();
        }
        return response()->json($users);
    }
    public function store(Request $request){
        // return $request;
        if(!$request->input("name")){
            return 'El campo nombre no puede estar vacío';
        }
        if(!$request->input("email")){
            return 'El campo email no puede estar vacío';
        }
        if(!$request->input("pwd")){
            return 'El campo contraseña no puede estar vacío';
        }
        $user = User::where('name',$request->input("email"))->first();
        if($user){
            return 'El correo indicado ya está en uso';
        }
        $user = new User();
        $user->name=$request->input("name");
        $user->email = $request->input("email");
        // $user->role = 
        $user->save();
        return "ok";
    }
}
