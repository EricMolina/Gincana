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
            $completed_points = 0;
            
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
                    $completed_points++;
                } else {
                    $show_next_hint = false;
                }

                array_push(
                    $available_points,
                    $current_available_point
                );
            }

            if ($completed_points == count($gincana_points)) {

                $last_session_point = GincanaPoint::where(
                    'gincana_id', $data['gincana']->id
                )->orderBy(
                    'order_id'
                )->get()->last();

                $session_groups = GincanaSessionGroup::where(
                    'gincana_session_id', $current_gincana_session_group->gincana_session_id
                )->get();

                $groups_in_last_point = [];

                foreach ($session_groups as $group) {
                    
                    $session_group_members = GincanaSessionGroupUser::where(
                        'gin_ses_group_id', $group->id
                    )->get();

                    $members_in_last_point_count = 0;

                    foreach ($session_group_members as $group_member) {

                        $member_last_checkpoint = GincanaSessionGroupUserCheckpoint::where(
                            'gin_ses_grp_user_id', $group_member->id
                        )->where(
                            'gincana_point_id', $last_session_point->id
                        );

                        if ($member_last_checkpoint->exists()) {
                            $members_in_last_point_count++;
                        }
                    }

                    if (count($session_group_members) == $members_in_last_point_count) {
                        array_push(
                            $groups_in_last_point,
                            $group->id
                        );
                    }
                }

                $groups_order = GincanaSessionGroupUserCheckpoint::with('gincanaSessionGroupUser')
                ->whereHas('gincanaSessionGroupUser', 
                    function ($query) use ($groups_in_last_point) {
                        return $query->whereIn('gin_ses_group_id', $groups_in_last_point);
                    }
                )->where(
                    'gincana_point_id', $last_session_point->id
                )->orderBy(
                    'created_at'
                )->get()->groupBy(
                    'gincanaSessionGroupUser.gin_ses_group_id'
                );

                $group_ranking = [];
                $position = 1;

                foreach ($groups_order as $group_id => $group_members) {
                    $group = GincanaSessionGroup::find($group_id);

                    array_push(
                        $group_ranking,
                        [
                            'group_name' => $group->name,
                            'group_position' => $position
                        ]
                    );

                    $position++;
                }

                $data['ranking'] = $group_ranking;
            }

            $data['available_points'] = $available_points;
        }

        return $data;
    }


    function checkpoint(Request $request) {
        $data = [];

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
            $data['result'] = 'wait';

        } else {
            $distance = calculateDistance($current_user_x_coord, $current_user_y_coord, $nex_point->coord_x, $nex_point->coord_y);
    
            if ($distance <= 150) { # metros
                $user_checkpoint = new GincanaSessionGroupUserCheckpoint;
                $user_checkpoint->gin_ses_grp_user_id = $group_user->id;
                $user_checkpoint->gincana_point_id = $next_gincana_point->id;
                $user_checkpoint->save();
    
                $data['result'] = 'ok';

            } else {
                $data['result'] = 'no';
            } 
        }

        return $data;
    }
}
