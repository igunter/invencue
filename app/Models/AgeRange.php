<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgeRange extends Model
{
    protected $fillable = [
        'is_active',
        'slug',
        'name',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function games()
    {
        return $this->hasMany(Game::class, 'age_range_slug', 'slug');
    }
}
