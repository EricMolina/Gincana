<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLabel extends Model
{
    protected $table = 'user_labels';
    use HasFactory;

    public function points() {
        return $this->belongsToMany(Point::class, 
            'user_labels_points', 'user_label_id', 'point_id');
    }
}
