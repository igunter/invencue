<?php

namespace App\Http\Controllers;

use App\Models\GameResult;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $daily = GameResult::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDays(27)->startOfDay())
            ->selectRaw('DATE(created_at) as day, SUM(score) as total_score, SUM(total) as total_questions')
            ->groupBy('day')
            ->get()
            ->keyBy('day');

        $dailyAccuracy = [];
        for ($i = 27; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $row = $daily->get($date);
            $dailyAccuracy[$date] = ($row && $row->total_questions > 0)
                ? round(100 * $row->total_score / $row->total_questions, 1)
                : null;
        }

        $byCategory = GameResult::where('user_id', $user->id)
            ->selectRaw('category_slug, COUNT(*) as games_played, SUM(score) as total_score, SUM(total) as total_questions, MAX(created_at) as last_played')
            ->groupBy('category_slug')
            ->orderByDesc('last_played')
            ->get();

        $byGame = GameResult::where('user_id', $user->id)
            ->selectRaw('category_slug, game_slug, COUNT(*) as games_played, SUM(score) as total_score, SUM(total) as total_questions, MAX(created_at) as last_played')
            ->groupBy('category_slug', 'game_slug')
            ->orderByDesc('last_played')
            ->get();

        $overall = GameResult::where('user_id', $user->id)
            ->selectRaw('SUM(score) as s, SUM(total) as t')
            ->first();
        $userAvg = ($overall && $overall->t > 0) ? round(100 * $overall->s / $overall->t, 1) : null;

        $peerAvg = null;
        if ($user->age_range) {
            $peer = GameResult::query()
                ->join('users', 'users.id', '=', 'game_results.user_id')
                ->where('users.age_range', $user->age_range)
                ->where('users.id', '!=', $user->id)
                ->selectRaw('SUM(game_results.score) as s, SUM(game_results.total) as t')
                ->first();
            if ($peer && $peer->t > 0) {
                $peerAvg = round(100 * $peer->s / $peer->t, 1);
            }
        }

        return view('dashboard', compact('dailyAccuracy', 'byCategory', 'byGame', 'userAvg', 'peerAvg'));
    }
}
