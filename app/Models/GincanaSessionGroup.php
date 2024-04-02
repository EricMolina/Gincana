<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GincanaSession;
use App\Models\GincanaSessionGroupUser;

class GincanaSessionGroup extends Model
{
    protected $table = 'gin_ses_groups';
    use HasFactory;

    public function gincanaSession() {
        return $this->belongsTo(GincanaSession::class, 'gincana_session_id');
    }

    public function gincanaSessionGroupUsers() {
        return $this->hasMany(GincanaSessionGroupUser::class, 'gin_ses_group_id');
    }
}
