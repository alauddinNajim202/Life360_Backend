<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CircleInvite extends Model
{
    use HasFactory;

    protected $fillable = [
        'circle_id',
        'invite_code',
        'relation_tag',
        'is_used',
        'expires_at',
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function circle()
    {
        return $this->belongsTo(Circle::class);
    }
}
