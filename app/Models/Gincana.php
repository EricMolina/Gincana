<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GincanaPoint;
use App\Models\GincanaSession;
use App\Models\User;

class Gincana extends Model
{
    use HasFactory;

    public function gincana_points() {
        return $this->hasMany(GincanaPoint::class, 'gincana_id');
    }

    public function gincana_sessions() {
        return $this->hasMany(GincanaSession::class, 'gincana_id');
    }

    public function gincana_creator() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
