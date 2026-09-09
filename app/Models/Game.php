<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'is_active',
        'sort_order',
        'age_range_slug',
        'category_slug',
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

    public function scopeOrdered(Builder $query): Builder
    {
        return $query
            ->orderByRaw('sort_order IS NULL')
            ->orderBy('sort_order')
            ->orderBy('name');
    }
}
