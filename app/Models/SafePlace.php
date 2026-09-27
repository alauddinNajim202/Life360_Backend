<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SafePlace extends Model
{
    use HasFactory;

    protected $fillable = ['circle_id', 'name', 'lat', 'lng', 'radius', 'created_by'];

    public function circle()
    {
        return $this->belongsTo(Circle::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
