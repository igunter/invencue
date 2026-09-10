@extends('layouts.app')

@section('meta_title', 'Edit Question - ' . config('app.name'))
@section('robots', 'noindex, nofollow')

@section('content')
    <a href="{{ route('admin.questions.show', $game) }}" class="d-inline-block mb-3 small"><i class="bi bi-arrow-left me-1"></i>Back to {{ $game->name }}</a>

    <h1 class="h4 mb-4">Edit question</h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.questions.update', $question) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-sm-4">
                        <label for="type" class="form-label">Type</label>
                        <input id="type" type="text" class="form-control @error('type') is-invalid @enderror" name="type" value="{{ old('type', $question->type) }}" required>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-8">
                        <label for="answer" class="form-label">Correct answer</label>
                        <input id="answer" type="text" class="form-control @error('answer') is-invalid @enderror" name="answer" value="{{ old('answer', $question->answer) }}" required>
                        @error('answer')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="question" class="form-label">Question text</label>
                        <textarea id="question" class="form-control @error('question') is-invalid @enderror" name="question" rows="2" required>{{ old('question', $question->question) }}</textarea>
                        @error('question')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="choices" class="form-label">Choices (optional)</label>
                        <textarea id="choices" class="form-control @error('choices') is-invalid @enderror" name="choices" rows="3" placeholder="One option per line, including the correct answer. Leave blank to auto-generate.">{{ old('choices', $question->choices ? implode("\n", $question->choices) : '') }}</textarea>
                        @error('choices')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input id="is_active" type="checkbox" class="form-check-input" name="is_active" value="1" {{ old('is_active', $question->is_active) ? 'checked' : '' }}>
                            <label for="is_active" class="form-check-label">Active (shown in the game)</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Save changes</button>
            </form>
        </div>
    </div>
@endsection
