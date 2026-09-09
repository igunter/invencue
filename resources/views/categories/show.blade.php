@extends('layouts.app')

@section('title', $category->name)

@section('content')
    <div id="categoryHeaderSentinel"></div>
    <div class="category-header hero-kicker-wrap p-5 mb-4 rounded-4 text-center">
        <div class="hero-kicker mb-3">{{ $category->name }}</div>
        <h1 class="section-title mb-0">{{ $category->name }} games</h1>

        <div class="age-picker justify-content-center mt-4">
            @foreach ($age_ranges as $age_range)
                <a href="{{ route('age-range.select', ['age_range' => $age_range->slug]) }}" class="age-btn age-range-button{{ $selected_age_range === $age_range->slug ? ' active selected' : '' }}">
                    {{ $age_range->name }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-9">
            @foreach ($age_ranges as $age_range)
                @php $ageGames = $games->where('age_range_slug', $age_range->slug); @endphp
                @continue($ageGames->isEmpty())
                @php $isSelectedAgeRange = $selected_age_range === $age_range->slug; @endphp

                <section id="age-range-{{ $age_range->slug }}" class="age-range-section feature-strip p-3 p-md-4 mb-4{{ $isSelectedAgeRange ? ' active-range' : '' }}" @if ($isSelectedAgeRange) data-active-range @endif>
                    <h2 class="h5 fw-bold mb-3">Ages {{ $age_range->name }}</h2>
                    <div class="row g-3">
                        @foreach ($ageGames as $game)
                            <div class="col-sm-6">
                                <a href="{{ route('game.show', ['category' => $category->slug, 'game' => $game->slug]) }}" class="game-card game-button d-flex align-items-center gap-3">
                                    @if ($game->icon)
                                        <div class="game-card-icon"><i class="bi {{ $game->icon }}"></i></div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $game->name }}</div>
                                        @if ($game->blurb)
                                            <div class="text-secondary small">{{ $game->blurb }}</div>
                                        @endif
                                    </div>
                                </a>
                            </div>
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

        const categoryHeaderSentinel = document.getElementById('categoryHeaderSentinel');
        if (categoryHeaderSentinel && categoryHeader) {
            new IntersectionObserver(
                ([entry]) => categoryHeader.classList.toggle('is-stuck', !entry.isIntersecting),
                { rootMargin: `-${fixedNavbar ? fixedNavbar.offsetHeight + 1 : 1}px 0px 0px 0px`, threshold: 0 }
            ).observe(categoryHeaderSentinel);
        }

        const activeRangeSection = document.querySelector('[data-active-range]');
        if (activeRangeSection) {
            activeRangeSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    </script>
@endpush
