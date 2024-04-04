<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;

    public function user_labels() {
        return $this->belongsToMany(UserLabel::class, 
            'user_labels_points', 'point_id', 'user_label_id');
    }

    public function labels() {
        return $this->belongsToMany(Label::class, 
            'points_labels', 'point_id', 'label_id');
    }
    public function gincana_points() {
        return $this->hasMany(GincanaPoint::class, 'point_id');
    }
    public function main_label() {
        return $this->belongsTo(Label::class, 'label_id');
    }
}
