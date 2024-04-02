<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GincanaSessionGroupUser;
use App\Models\GincanaPoint;

class GincanaSessionGroupUserCheckpoint extends Model
{
    protected $table = 'gin_ses_grp_usr_checkpoints';
    use HasFactory;

    public function gincanaSessionGroupUser() {
        return $this->belongsTo(GincanaSessionGroupUser::class, 'gin_ses_grp_user_id');
    }

    public function gincanaPoint() {
        return $this->belongsTo(GincanaPoint::class, 'gincana_point_id');
    }
}
