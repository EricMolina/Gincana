<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Gincana;
use App\Models\User;
use App\Models\GincanaSessionGroup;

class GincanaSession extends Model
{
    protected $table = 'gincana_sessions';
    use HasFactory;
    
    public function gincana() {
        return $this->belongsTo(Gincana::class, 'gincana_id');
    }

    public function session_admin() {
        return $this->belongsTo(User::class, 'session_admin');
    }

    public function gincana_session_groups() {
        return $this->hasMany(GincanaSessionGroup::class, 'gincana_session_id');
    }
}
