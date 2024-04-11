<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Point;
use App\Models\User;
use Illuminate\Http\Request;

class UserLabel extends Model
{
    protected $table = 'user_labels';
    use HasFactory;

    public function points() {
        return $this->belongsToMany(Point::class, 
            'user_labels_points', 'user_label_id', 'point_id');
    }
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
