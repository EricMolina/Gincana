<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(){
        return view("admin.user.index");
    }
    public function list(Request $request){
        $src=$request->src;
        if($src == ""){
            $users = User::all();
        }else{
            $users = User::where("name","LIKE",$src."%")->get();
        }
        return response()->json($users);
    }
    public function store(Request $request){
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
        $filename = time().'.'.$request->file("img")->getClientOriginalExtension();
        $user = new User();
        $user->img = $filename;
        $user->name=$request->input("name");
        $user->email = $request->input("email");
        $user->role = $request->input("rol");
        $user->password = Hash::make($request->input("pwd"));
        $user->save();
        $request->file("img")->move(public_path('img/users'), $filename);
        return "ok";
    }
    public function delete(Request $request){
        // DB::beginTransaction();
        $id = $request->input("id");
        try {
            $user = User::find($id);

            $user->delete();
            $imgName = $user->img;
            if(File::exists("img/users/$imgName")){
                File::delete("img/users/$imgName");
            }
            // DB::commit();
            return "ok";
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
    public function show(Request $request){
        $id = $request->input("id");
        $user = User::find($id);
        return response()->json($user);
    }
}
