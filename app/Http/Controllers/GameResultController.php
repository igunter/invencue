<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GameResultController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'category_slug' => ['required', 'string', 'max:255'],
            'game_slug' => ['required', 'string', 'max:255'],
            'score' => ['required', 'integer', 'min:0'],
            'total' => ['required', 'integer', 'min:1'],
        ]);

        $data['score'] = min($data['score'], $data['total']);

        $request->user()->gameResults()->create($data);

        return response()->json(['status' => 'ok'], 201);
    }
}
