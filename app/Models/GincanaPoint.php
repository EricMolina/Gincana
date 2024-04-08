<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Gincana;
use App\Models\Point;
use App\Models\GincanaSessionGroupUserCheckpoint;

class GincanaPoint extends Model
{
    protected $table = 'gincana_points';
    protected $fillable = ['gincana_id', 'point_id', 'order_id', 'hint', 'user_id'];
    use HasFactory;

    public function gincana() {
        return $this->belongsTo(Gincana::class, 'gincana_id');
    }

    public function point() {
        return $this->belongsTo(Point::class, 'point_id');
    }

    public function gincana_sessions_checkpoints() {
        return $this->hasMany(GincanaSessionGroupUserCheckpoint::class, 'gincana_point_id');
    }


}
