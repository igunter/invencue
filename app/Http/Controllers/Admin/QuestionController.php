<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Question;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuestionController extends Controller
{
    public function index(): View
    {
        $games = Game::withCount('questions')
            ->with('category')
            ->ordered()
            ->get()
            ->groupBy(fn (Game $game) => $game->category?->name ?? $game->category_slug);

        return view('admin.questions.index', compact('games'));
    }

    public function show(Game $game): View
    {
        $questions = $game->questions()->orderBy('type')->orderBy('id')->get()->groupBy('type');

        return view('admin.questions.show', compact('game', 'questions'));
    }

    public function store(Request $request, Game $game): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'string', 'max:100'],
            'question' => ['required', 'string'],
            'answer' => ['required', 'string', 'max:255'],
            'choices' => ['nullable', 'string'],
        ]);

        $choices = $this->parseChoices($validated['choices'] ?? null);

        $game->questions()->create([
            'type' => $validated['type'],
            'question' => $validated['question'],
            'answer' => $validated['answer'],
            'choices' => $choices,
        ]);

        return redirect()->route('admin.questions.show', $game)->with('status', 'Question added.');
    }

    public function edit(Question $question): View
    {
        return view('admin.questions.edit', ['question' => $question, 'game' => $question->game]);
    }

    public function update(Request $request, Question $question): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'string', 'max:100'],
            'question' => ['required', 'string'],
            'answer' => ['required', 'string', 'max:255'],
            'choices' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $question->update([
            'type' => $validated['type'],
            'question' => $validated['question'],
            'answer' => $validated['answer'],
            'choices' => $this->parseChoices($validated['choices'] ?? null),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.questions.show', $question->game)->with('status', 'Question updated.');
    }

    public function destroy(Question $question): RedirectResponse
    {
        $game = $question->game;
        $question->delete();

        return redirect()->route('admin.questions.show', $game)->with('status', 'Question deleted.');
    }

    private function parseChoices(?string $raw): ?array
    {
        if (! $raw || trim($raw) === '') {
            return null;
        }

        $lines = array_values(array_filter(array_map('trim', explode("\n", $raw)), fn ($line) => $line !== ''));

        return $lines ?: null;
    }
}
