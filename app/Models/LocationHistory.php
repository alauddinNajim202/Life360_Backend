<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationHistory extends Model
{
    public $timestamps = false; // Disable default timestamps if only using recorded_at

    protected $fillable = ['user_id', 'lat', 'lng', 'speed_kmh', 'recorded_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
