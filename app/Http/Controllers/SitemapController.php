<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Game;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $categories = Category::where('is_active', true)->ordered()->get();
        $games = Game::where('is_active', true)->ordered()->get();

        $urls = [];

        $urls[] = [
            'loc' => route('welcome'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'weekly',
            'priority' => '1.0',
        ];

        foreach (['terms', 'privacy', 'faq'] as $routeName) {
            $urls[] = [
                'loc' => route($routeName),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'yearly',
                'priority' => '0.3',
            ];
        }

        $urls[] = [
            'loc' => route('contact.show'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'yearly',
            'priority' => '0.3',
        ];

        foreach ($categories as $category) {
            $urls[] = [
                'loc' => route('category.show', $category),
                'lastmod' => $category->updated_at?->toAtomString() ?? now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        }

        foreach ($games as $game) {
            $urls[] = [
                'loc' => route('game.show', ['category' => $game->category_slug, 'game' => $game]),
                'lastmod' => $game->updated_at?->toAtomString() ?? now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ];
        }

        $xml = view('sitemap.index', compact('urls'))->render();

        return response($xml, 200)->header('Content-Type', 'text/xml');
    }
}
