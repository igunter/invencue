@extends('layouts.app')

@section('title', $category->name)

@section('content')
    <div class="p-5 mb-4 bg-body-tertiary rounded-3 text-center">
        <h1 class="display-6 fw-bold">{{ $category->name }}</h1>

        <div class="mt-4">
            @foreach ($age_ranges as $age_range)
                <a href="{{ route('age-range.select', ['age_range' => $age_range->slug]) }}" class="btn btn-outline-secondary btn-lg m-1 age-range-button{{ $selected_age_range === $age_range->slug ? ' selected' : '' }}">
                    {{ $age_range->name }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="p-3 mb-4 bg-body-tertiary rounded-3">
                <div class="list-group">
                    @foreach ($games as $game)
                        <a href="{{ route('game.show', ['category' => $category->slug, 'game' => $game->slug]) }}" class="list-group-item list-group-item-action game-button d-flex align-items-center gap-3">
                            @if ($game->icon)
                                <i class="bi {{ $game->icon }} fs-1"></i>
                            @endif
                            <div>
                                <div class="fs-4 fw-semibold">{{ $game->name }}</div>
                                @if ($game->blurb)
                                    <div class="text-secondary">{{ $game->blurb }}</div>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.age-range-button').forEach((button) => {
            button.addEventListener('click', (event) => {
                event.preventDefault();

                window.confirmAgeRangeChange(button, () => {
                    window.location.href = button.href;
                });
            });
        });
    </script>
@endpush
