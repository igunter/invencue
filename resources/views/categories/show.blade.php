@extends('layouts.app')

@section('title', $category->name)

@section('content')
    <div class="category-header p-5 mb-4 bg-body-tertiary rounded-3 text-center">
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
            @foreach ($age_ranges as $age_range)
                @php $ageGames = $games->where('age_range_slug', $age_range->slug); @endphp
                @continue($ageGames->isEmpty())
                @php $isSelectedAgeRange = $selected_age_range === $age_range->slug; @endphp

                <section id="age-range-{{ $age_range->slug }}" class="age-range-section p-3 mb-4 bg-body-tertiary rounded-3{{ $isSelectedAgeRange ? ' active-range' : '' }}" @if ($isSelectedAgeRange) data-active-range @endif>
                    <h2 class="h5 mb-3">Ages {{ $age_range->name }}</h2>
                    <div class="list-group">
                        @foreach ($ageGames as $game)
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
                </section>
            @endforeach
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

        const categoryHeader = document.querySelector('.category-header');
        const fixedNavbar = document.querySelector('nav.fixed-top');
        if (categoryHeader && fixedNavbar) {
            const navbarHeight = fixedNavbar.offsetHeight;
            categoryHeader.style.top = navbarHeight + 'px';
            document.documentElement.style.scrollPaddingTop = (navbarHeight + categoryHeader.offsetHeight) + 'px';
        }

        const activeRangeSection = document.querySelector('[data-active-range]');
        if (activeRangeSection) {
            activeRangeSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    </script>
@endpush
