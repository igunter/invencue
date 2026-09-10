@extends('layouts.app')

@section('meta_title', 'Manage Questions - ' . config('app.name'))
@section('robots', 'noindex, nofollow')

@section('content')
    <h1 class="h4 mb-4">Manage questions</h1>

    @foreach ($games as $categoryName => $categoryGames)
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="h6 text-uppercase text-secondary mb-3">{{ $categoryName }}</h2>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Game</th>
                                <th class="text-end">Questions in database</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categoryGames as $game)
                                <tr>
                                    <td>{{ $game->name }}</td>
                                    <td class="text-end">
                                        @if ($game->questions_count > 0)
                                            <span class="badge bg-success">{{ $game->questions_count }}</span>
                                        @else
                                            <span class="badge bg-secondary">0</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.questions.show', $game) }}" class="btn btn-sm btn-outline-primary">Manage</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach
@endsection
