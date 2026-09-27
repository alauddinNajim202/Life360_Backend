<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journey extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'circle_id', 'destination', 'dest_lat', 'dest_lng', 'eta_minutes', 'status', 'started_at', 'arrived_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
