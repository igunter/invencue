<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameResult extends Model
{
    protected $fillable = [
        'user_id',
        'category_slug',
        'game_slug',
        'score',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'total' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
