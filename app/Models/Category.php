<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'is_active',
        'sort_order',
        'slug',
        'name',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function games()
    {
        return $this->hasMany(Game::class, 'category_slug', 'slug');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query
            ->orderByRaw('sort_order IS NULL')
            ->orderBy('sort_order')
            ->orderBy('name');
    }
}
