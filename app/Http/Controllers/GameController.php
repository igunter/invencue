<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Game;

class GameController extends Controller
{
    public function index(Category $category)
    {
        $games = Game::where('category_slug', $category->slug)->ordered()->get();

        return response()->json($games);
    }

    public function show(Category $category, Game $game)
    {
        $dbQuestions = $game->questions()->where('is_active', true)->get()->groupBy('type');

        return view('games.' . $category->slug . '.' . $game->slug, compact('category', 'game', 'dbQuestions'));
    }
}
