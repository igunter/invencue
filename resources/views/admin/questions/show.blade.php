@extends('layouts.app')

@section('title', $game->name . ' Questions - ' . config('app.name'))

@section('content')
    <a href="{{ route('admin.questions.index') }}" class="d-inline-block mb-3 small"><i class="bi bi-arrow-left me-1"></i>All games</a>

    <h1 class="h4 mb-1">{{ $game->name }}</h1>
    <p class="text-secondary mb-4">Questions stored here are added to the game's existing question pool. Leave "choices" blank to auto-generate wrong answers from the other questions of the same type.</p>

    <div class="card mb-4">
        <div class="card-body">
            <h2 class="h6 text-uppercase text-secondary mb-3">Add a question</h2>
            <form method="POST" action="{{ route('admin.questions.store', $game) }}">
                @csrf

                <div class="row g-3">
                    <div class="col-sm-4">
                        <label for="type" class="form-label">Type</label>
                        <input id="type" type="text" class="form-control @error('type') is-invalid @enderror" name="type" value="{{ old('type', 'main') }}" required>
                        <div class="form-text">Must match one of this game's question type ids (usually "main").</div>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-8">
                        <label for="answer" class="form-label">Correct answer</label>
                        <input id="answer" type="text" class="form-control @error('answer') is-invalid @enderror" name="answer" value="{{ old('answer') }}" required>
                        @error('answer')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="question" class="form-label">Question text</label>
                        <textarea id="question" class="form-control @error('question') is-invalid @enderror" name="question" rows="2" required>{{ old('question') }}</textarea>
                        @error('question')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="choices" class="form-label">Choices (optional)</label>
                        <textarea id="choices" class="form-control @error('choices') is-invalid @enderror" name="choices" rows="3" placeholder="One option per line, including the correct answer. Leave blank to auto-generate.">{{ old('choices') }}</textarea>
                        @error('choices')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Add question</button>
            </form>
        </div>
    </div>

    @forelse ($questions as $type => $typeQuestions)
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="h6 text-uppercase text-secondary mb-3">Type: {{ $type }} ({{ $typeQuestions->count() }})</h2>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Question</th>
                                <th>Answer</th>
                                <th>Choices</th>
                                <th></th>
                                <th class="text-end"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($typeQuestions as $question)
                                <tr class="{{ $question->is_active ? '' : 'text-secondary' }}">
                                    <td>{{ $question->question }}</td>
                                    <td>{{ $question->answer }}</td>
                                    <td class="small text-secondary">{{ $question->choices ? implode(', ', $question->choices) : '—' }}</td>
                                    <td>
                                        @unless ($question->is_active)
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endunless
                                    </td>
                                    <td class="text-end text-nowrap">
                                        <a href="{{ route('admin.questions.edit', $question) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        <form method="POST" action="{{ route('admin.questions.destroy', $question) }}" class="d-inline" onsubmit="return confirm('Delete this question?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @empty
        <p class="text-secondary">No questions in the database for this game yet — it's still using its built-in question set.</p>
    @endforelse
@endsection
