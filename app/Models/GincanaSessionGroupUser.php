<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GincanaSessionGroup;
use App\Models\GincanaSessionGroupUserCheckpoint;

class GincanaSessionGroupUser extends Model
{
    protected $table = 'gin_ses_grp_users';
    use HasFactory;

    public function gincanaSessionGroup() {
        return $this->belongsTo(GincanaSessionGroup::class, 'gin_ses_group_id');
    }

    public function checkpoints() {
        return $this->hasMany(GincanaSessionGroupUserCheckpoint::class, 'gin_ses_grp_user_id');
    }
}
