<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Circle extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color_theme', 'icon', 'owner_id'];

    public function getIconAttribute($value): ?string
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        if (request()->is('api/*') && ! empty($value)) {
            return url($value);
        }

        return $value;
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class)->withPivot('role', 'relation_tag', 'joined_at');
    }

    public function safePlaces()
    {
        return $this->hasMany(SafePlace::class);
    }

    public function invites()
    {
        return $this->hasMany(CircleInvite::class);
    }
}
