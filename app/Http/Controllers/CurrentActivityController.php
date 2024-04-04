<?php

namespace App\Http\Controllers;

use App\Models\Gincana;
use App\Models\GincanaSession;
use App\Models\GincanaSessionGroup;
use App\Models\GincanaPoint;
use App\Models\GincanaSessionGroupUser;
use App\Models\GincanaSessionGroupUserCheckpoint;
use App\Models\Point;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;


function calculateDistance($user_x_pos, $user_y_pox, $point_x_pos, $point_y_pos) {
    $earthRadius = 6371000;

    $dLat = deg2rad($point_x_pos - $user_x_pos);
    $dLon = deg2rad($point_y_pos - $user_y_pox);

    $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($user_x_pos)) * cos(deg2rad($point_x_pos)) * sin($dLon / 2) * sin($dLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    return $earthRadius * $c;
}


class CurrentActivityController extends Controller
{
    function status() {
        $data = [];

        $current_gincana_session_group = GincanaSessionGroup::withCount(
            'gincanaSessionGroupUsers'
        )->with(
            'gincanaSessionGroupUsers.user'
        )->find(
            Session::get('current_activity')
        );

        $data['group'] = $current_gincana_session_group;

        $data['session'] = GincanaSession::find($current_gincana_session_group->gincana_session_id);

        $data['gincana'] = Gincana::find($data['session']->gincana_id);

        if ($data['session']->status == 1) {

            $available_points = [];
            $group_members = GincanaSessionGroupUser::where('gin_ses_group_id', $current_gincana_session_group->id)->get();
            $group_count = count($group_members);

            $gincana_points = GincanaPoint::where(
                'gincana_id', $data['gincana']->id
            )->orderBy(
                'order_id'
            )->get();

            $show_next_hint = true;
            
            foreach ($gincana_points as $point) {
                
                $current_point_count = 0;
                
                foreach ($group_members as $member) {
                    $member_checkpoint = GincanaSessionGroupUserCheckpoint::where(
                        'gin_ses_grp_user_id', $member->id
                    )->where(
                        'gincana_point_id', $point->id
                    );

                    if ($member_checkpoint->exists()) {
                        $current_point_count++;
                    }
                }

                $current_available_point = [
                    'members_in_point' => $current_point_count,
                    'point_id' => $point->id
                ];

                if ($show_next_hint) {
                    $current_available_point['hint'] = $point->hint;
                }

                if ($current_point_count == $group_count) {
                    $point_data = Point::find($point->point_id);
                    $current_available_point['coord_x'] = $point_data->coord_x;
                    $current_available_point['coord_y'] = $point_data->coord_y;
                    $show_next_hint = true;
                } else {
                    $show_next_hint = false;
                }

                array_push(
                    $available_points,
                    $current_available_point
                );
            }

            $data['available_points'] = $available_points;
        }

        return $data;
    }


    function checkpoint(Request $request) {
        $current_user_x_coord = $request->coord_x;
        $current_user_y_coord = $request->coord_y;

        $current_gincana_session_group = GincanaSessionGroup::find(
            Session::get('current_activity')
        );
        $session = GincanaSession::find($current_gincana_session_group->gincana_session_id);
        $gincana = Gincana::find($session->gincana_id);

        $gincana_points = GincanaPoint::where(
            'gincana_id', $gincana->id
        )->orderBy(
            'order_id'
        )->get();

        $next_gincana_point = null;
        $group_members = GincanaSessionGroupUser::where('gin_ses_group_id', $current_gincana_session_group->id)->get();
        $group_count = count($group_members);

        foreach ($gincana_points as $point) {
                
            $current_point_count = 0;
            
            foreach ($group_members as $member) {
                $member_checkpoint = GincanaSessionGroupUserCheckpoint::where(
                    'gin_ses_grp_user_id', $member->id
                )->where(
                    'gincana_point_id', $point->id
                );

                if ($member_checkpoint->exists()) {
                    $current_point_count++;
                }
            }

            if ($current_point_count != $group_count) {
                $next_gincana_point = $point;
                break;
            }
        }

        $group_user = GincanaSessionGroupUser::where(
            'user_id', Auth::user()->id
        )->where(
            'gin_ses_group_id', $current_gincana_session_group->id
        )->first();

        $nex_point = Point::find($next_gincana_point->id);

        $next_point_checkpoint = GincanaSessionGroupUserCheckpoint::where(
            'gin_ses_grp_user_id', $group_user->id
        )->where(
            'gincana_point_id', $next_gincana_point->id
        );

        if ($next_point_checkpoint->exists()) {
            return 'wait';
        }

        $distance = calculateDistance($current_user_x_coord, $current_user_y_coord, $nex_point->coord_x, $nex_point->coord_y);

        if ($distance <= 150) { # metros
            $user_checkpoint = new GincanaSessionGroupUserCheckpoint;
            $user_checkpoint->gin_ses_grp_user_id = $group_user->id;
            $user_checkpoint->gincana_point_id = $next_gincana_point->id;
            $user_checkpoint->save();

            return 'ok';
        }

        return 'no';
    }
}
