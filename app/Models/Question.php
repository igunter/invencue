<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'game_slug',
        'type',
        'question',
        'answer',
        'choices',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'choices' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_slug', 'slug');
    }
}
