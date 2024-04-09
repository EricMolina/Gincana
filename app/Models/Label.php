<?php

namespace App\Models;
use App\Models\Point;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;

    public function points() {
        return $this->belongsToMany(Point::class, 
            'points_labels', 'label_id', 'point_id');
    }
    public function main_points() {
        return $this->hasMany(Point::class, 'point_id');
    }
}
