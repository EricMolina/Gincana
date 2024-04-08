<?php

namespace App\Http\Controllers;

use App\Models\GincanaSessionGroup;
use App\Models\GincanaSessionGroupUser;
use App\Models\GincanaSessionGroupUserCheckpoint;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Exception;

class GincanaSessionGroupController extends Controller
{
    function list(Request $request) {
        return GincanaSessionGroup::with('gincanaSessionGroupUsers.user')
                                  ->where('gincana_session_id', $request->id)
                                  ->get();
    }


    function store(Request $request) {
        try {
            DB::beginTransaction();
            
            $gincana_session_group = new GincanaSessionGroup;
            $gincana_session_group->name = $request->name;
            $gincana_session_group->status = 1;
            $gincana_session_group->gincana_session_id = $request->gincana_session_id;
            $gincana_session_group->save();

            $gincana_session_group_user = new GincanaSessionGroupUser;
            $gincana_session_group_user->user_id = Auth::user()->id;
            $gincana_session_group_user->gin_ses_group_id = $gincana_session_group->id;
            $gincana_session_group_user->save();

            Session::put('current_activity', $gincana_session_group->id);

            DB::commit();

            return $gincana_session_group;

        } catch (Exception $e) {
            DB::rollBack();
            return "error: ".$e->getMessage();
        }
    }


    function join(Request $request) {
        try {
            
            $gincana_session_group_user = new GincanaSessionGroupUser;
            $gincana_session_group_user->user_id = Auth::user()->id;
            $gincana_session_group_user->gin_ses_group_id = $request->gincana_session_group_id;
            $gincana_session_group_user->save();

            Session::put('current_activity', $request->gincana_session_group_id);

            return 'ok';

        } catch (Exception $e) {
            return "error: ".$e->getMessage();
        }
    }


    function exit() {
        try {
            DB::beginTransaction();

            $gincana_session_group_user = GincanaSessionGroupUser::where(
                'gin_ses_group_id', Session::get('current_activity')
            )->where(
                'user_id', Auth::user()->id
            );

            GincanaSessionGroupUserCheckpoint::where(
                'gin_ses_grp_user_id', $gincana_session_group_user->first()->id
            )->delete();
            
            $gincana_session_group_user->delete();

            Session::forget('current_activity');

            DB::commit();

            return 'ok';
        
        } catch (Exception $e) {
            DB::rollBack();
            return "error: ".$e->getMessage();
        }
    }
}
