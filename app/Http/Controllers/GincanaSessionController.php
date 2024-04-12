<?php

namespace App\Http\Controllers;

use App\Models\GincanaSession;
use App\Models\GincanaSessionGroup;

use App\Http\Controllers\Controller;
use App\Models\Gincana;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Exception;


function generateRandomString($length = 8) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}


class GincanaSessionController extends Controller
{
    function list(Request $request) {
        $gincana_sessions = GincanaSession::with(
            'session_admin'
        )->where(
            'gincana_id', $request->id
        )->where(
            'status', 0
        )->get();

        return $gincana_sessions->map(function ($session) {
            $session->is_owner = Auth::user()->id == $session->session_admin;
            return $session;
        });
    }


    function store(Request $request) {
        try {
            if(!isset($request->name) || $request->name ==""){
                return "Error1";
            }
            // $labels = Label::where("name","LIKE",$src."%")->get();
            $gincana = GincanaSession::where("gincana_id", $request->gincana_id)
                ->where("session_admin", Auth::user()->id)
                ->where("status", 0)
                ->first();
            if($gincana){
                return "Error2";
            }
            $gincana_session = new GincanaSession;
            $gincana_session->name = $request->name;
            $gincana_session->status = 0;
            $gincana_session->gincana_id = $request->gincana_id;
            $gincana_session->session_admin = Auth::user()->id;
            $gincana_session->session_code = generateRandomString();
            $gincana_session->save();

            // return $gincana_session;
            return $gincana_session->session_code;

        } catch (Exception $e) {
            return "error: ".$e->getMessage();
        }
    }
    function newSession(){
        return view("session.newSession");
    }
    function newGroup(){
        return view("session.newGroup");
    }

    function start(Request $request) {
        try {

            $gincana_session_group = GincanaSessionGroup::find(
                Session::get('current_activity')
            );

            $gincana_session = GincanaSession::find($gincana_session_group->gincana_session_id);
            $gincana_session->status = 1;
            $gincana_session->save();

        } catch (Exception $e) {
            return "error: ".$e->getMessage();
        }
    }
}
