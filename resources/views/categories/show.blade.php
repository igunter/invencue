@extends('layouts.app')

@php
    $categoryBlurbs = [
        'maths'       => 'Free maths games for kids — addition, times tables, fractions, algebra, geometry and more, sorted by age range.',
        'english'     => 'Free English games for kids — spelling, grammar, punctuation, synonyms and reading skills, sorted by age range.',
        'biology'     => 'Free biology games for kids — cells, the human body, plants, genetics and ecosystems, sorted by age range.',
        'chemistry'   => 'Free chemistry games for kids — atoms, states of matter, reactions and the periodic table, sorted by age range.',
        'physics'     => 'Free physics games for kids — forces, energy, electricity, light and waves, sorted by age range.',
        'earth-space' => 'Free Earth & Space games for kids — the solar system, weather, rocks and the water cycle, sorted by age range.',
        'science-lab' => 'Free science lab games for kids — scientific method, measuring, graphs and lab safety, sorted by age range.',
        'history'     => 'Free history games for kids — ancient civilisations, monarchs, world wars and more, sorted by age range.',
        'geography'   => 'Free geography games for kids — maps, countries, flags, landforms and the world around us, sorted by age range.',
        'psychology'  => 'Free psychology games for kids — the brain, emotions, memory and how we learn, sorted by age range.',
        'computing'   => 'Free computing games for kids — binary, algorithms, networks and online safety, sorted by age range.',
        'art-design'  => 'Free art & design games for kids — colour theory, famous artists, art movements and techniques, sorted by age range.',
        'music'       => 'Free music games for kids — musical instruments, rhythm, notation and famous composers, sorted by age range.',
        'religious-education' => 'Free religious education games for kids — world religions, beliefs, festivals and holy books, sorted by age range.',
        'money-financial-literacy' => 'Free money & financial literacy games for kids — saving, budgeting, needs vs wants and earning, sorted by age range.',
    ];
    $categoryBlurb = $categoryBlurbs[$category->slug] ?? ('Free ' . $category->name . ' games for kids, sorted by age range — quick, interactive practice with no sign-up needed.');
@endphp

@section('meta_title', $category->name . ' Games for Kids - ' . config('app.name'))
@section('meta_blurb', $categoryBlurb)

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
