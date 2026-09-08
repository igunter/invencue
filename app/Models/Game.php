<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'is_active',
        'slug',
        'name',
        'icon',
        'blurb',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_slug', 'slug');
    }

    public function age_range()
    {
        return $this->belongsTo(AgeRange::class, 'age_range_slug', 'slug');
    }
}
