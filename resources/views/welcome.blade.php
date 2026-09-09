@extends('layouts.app')

@section('content')
    @php
        $categoryStyles = [
            'maths'       => ['icon' => 'bi-calculator',        'accent' => '#4e7cff', 'tint' => '#e9efff'],
            'english'     => ['icon' => 'bi-book',              'accent' => '#8a5cff', 'tint' => '#f2eaff'],
            'biology'     => ['icon' => 'bi-diagram-2',         'accent' => '#3ecb8c', 'tint' => '#e7fbf2'],
            'chemistry'   => ['icon' => 'bi-droplet-half',      'accent' => '#ff9f43', 'tint' => '#fff2e5'],
            'physics'     => ['icon' => 'bi-lightning-charge',  'accent' => '#8a5cff', 'tint' => '#f2eaff'],
            'earth-space' => ['icon' => 'bi-globe2',            'accent' => '#2dc8e7', 'tint' => '#e9f8ff'],
            'science-lab' => ['icon' => 'bi-clipboard',         'accent' => '#ff6fae', 'tint' => '#fff0f3'],
            'history'     => ['icon' => 'bi-hourglass-split',   'accent' => '#ff6fae', 'tint' => '#fff0f3'],
            'geography'   => ['icon' => 'bi-map',               'accent' => '#3ecb8c', 'tint' => '#e7fbf2'],
            'psychology'  => ['icon' => 'bi-emoji-smile',       'accent' => '#ff6fae', 'tint' => '#fff0f3'],
            'computing'   => ['icon' => 'bi-cpu',               'accent' => '#ffc83d', 'tint' => '#fff8e5'],
            'art-design'  => ['icon' => 'bi-palette',           'accent' => '#8a5cff', 'tint' => '#f2eaff'],
            'music'       => ['icon' => 'bi-music-note-beamed', 'accent' => '#ff6fae', 'tint' => '#fff0f3'],
            'religious-education' => ['icon' => 'bi-stars',     'accent' => '#2dc8e7', 'tint' => '#e9f8ff'],
            'money-financial-literacy' => ['icon' => 'bi-piggy-bank', 'accent' => '#ff9f43', 'tint' => '#fff2e5'],
        ];
        $defaultStyle = ['icon' => 'bi-grid', 'accent' => '#4e7cff', 'tint' => '#e9efff'];

        $visibleCategories = $categories->filter(function ($category) use ($selected_age_range) {
            return $category->games()
                ->where('is_active', true)
                ->when($selected_age_range, fn ($query) => $query->where('age_range_slug', $selected_age_range))
                ->exists();
        });
    @endphp

    <header class="hero">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <div class="hero-kicker">⚡ Learn. Play. Level up.</div>
                <h1 class="hero-h1">School stuff.<br><span>Game mode on.</span></h1>
                <p class="hero-copy">
                    Pick your age, choose a subject and get straight into fast, interactive games
                    designed to make tricky topics click.
                </p>

                <div class="mt-4 d-flex gap-2 flex-wrap">
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-main btn-lg">
                            <i class="bi bi-person-plus me-1"></i>Get started
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-light btn-lg rounded-pill px-4 fw-bold border">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Log in
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn btn-main btn-lg">
                            <i class="bi bi-speedometer2 me-1"></i>Go to dashboard
                        </a>
                    @endguest
                    <a href="#subjects" class="btn btn-light btn-lg rounded-pill px-4 fw-bold border">Explore subjects &rarr;</a>
                </div>

                <div class="mt-4">
                    <div class="fw-bold mb-2">🧒 Choose your age range</div>
                    <div class="age-picker" id="agePicker">
                        @foreach ($age_ranges as $age_range)
                            <a href="{{ route('age-range.select', ['age_range' => $age_range->slug]) }}" class="age-btn age-range-button{{ $selected_age_range === $age_range->slug ? ' active selected' : '' }}">
                                {{ $age_range->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="hero-art" aria-hidden="true">
                    <div class="art-panel"></div>

                    <svg class="art-main" viewBox="0 0 560 480">
                        <defs>
                            <linearGradient id="g1" x1="0" x2="1" y1="0" y2="1">
                                <stop offset="0" stop-color="#4e7cff"/>
                                <stop offset="1" stop-color="#8a5cff"/>
                            </linearGradient>
                            <linearGradient id="g2" x1="0" x2="1">
                                <stop offset="0" stop-color="#2dc8e7"/>
                                <stop offset="1" stop-color="#3ecb8c"/>
                            </linearGradient>
                        </defs>

                        <circle cx="280" cy="236" r="180" fill="#fff" opacity=".96"/>
                        <path d="M175 165c18-58 70-94 128-87 59 7 102 53 108 111 7 67-37 133-108 145-77 14-149-40-155-117-2-18 1-36 7-52Z" fill="url(#g1)" opacity=".10"/>

                        <rect x="150" y="156" width="260" height="174" rx="34" fill="#172946"/>
                        <rect x="178" y="181" width="204" height="112" rx="18" fill="#f8fbff"/>
                        <path d="M206 254 235 218l27 25 35-46 56 66" fill="none" stroke="#4e7cff" stroke-width="10" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="235" cy="218" r="9" fill="#ffc83d"/>
                        <circle cx="297" cy="197" r="9" fill="#ff6fae"/>
                        <rect x="222" y="320" width="116" height="24" rx="12" fill="#263866"/>

                        <path d="M115 210c-19 79 24 169 109 199 88 31 189-18 220-111" fill="none" stroke="#8a5cff" stroke-width="4" stroke-linecap="round" stroke-dasharray="7 12" opacity=".45"/>
                        <circle cx="111" cy="227" r="29" fill="#ffc83d"/>
                        <path d="M99 228h24M111 216v24" stroke="#172946" stroke-width="6" stroke-linecap="round"/>

                        <circle cx="442" cy="298" r="30" fill="url(#g2)"/>
                        <path d="M427 302c12-24 26-24 31-7-8 17-18 23-31 7Z" fill="#fff"/>
                        <path d="M443 282v41" stroke="#fff" stroke-width="4" stroke-linecap="round"/>

                        <circle cx="408" cy="112" r="26" fill="#ff6fae"/>
                        <path d="M395 120c9-19 18-26 27-22-2 17-10 27-27 22Z" fill="#fff"/>
                        <circle cx="400" cy="103" r="5" fill="#172946"/>

                        <g fill="#4e7cff" opacity=".65">
                            <path d="m136 112 5 12 12 5-12 5-5 12-5-12-12-5 12-5Z"/>
                            <path d="m443 188 4 9 9 4-9 4-4 9-4-9-9-4 9-4Z"/>
                        </g>
                    </svg>

                    <div class="floating-chip chip-a">🧠 +250 XP</div>
                    <div class="floating-chip chip-b">🚀 7 day streak</div>
                    <div class="floating-chip chip-c">✓ Level complete</div>
                </div>
            </div>
        </div>
    </header>

    <section class="section pt-2">
        <div class="feature-strip">
            <div class="row g-0">
                <div class="col-md-4">
                    <div class="mini-feature">
                        <div class="mini-icon">🎮</div>
                        <div><strong>Actually interactive</strong><small>No endless worksheets.</small></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mini-feature">
                        <div class="mini-icon">⚡</div>
                        <div><strong>Quick sessions</strong><small>Perfect for 5–15 minute bursts.</small></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mini-feature">
                        <div class="mini-icon">📈</div>
                        <div><strong>Built to progress</strong><small>Games get harder as you improve.</small></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="subjects">
        <div class="row align-items-end mb-4">
            <div class="col-lg-8">
                <div class="text-uppercase fw-black small text-primary mb-2" style="font-weight:900;letter-spacing:.08em">Pick a subject</div>
                <h2 class="section-title">🎮 What are you playing today?</h2>
                <p class="section-intro mb-0">Each subject has mini-games, challenges and quizzes tailored to your age range.</p>
            </div>
        </div>

        <div class="row g-4">
            @foreach ($visibleCategories as $category)
                @php $style = $categoryStyles[$category->slug] ?? $defaultStyle; @endphp
                <div class="col-6 col-lg-4">
                    <a href="{{ route('category.show', $category->slug) }}" class="subject-card category-button d-block text-reset" style="--cat-accent: {{ $style['accent'] }}; --cat-tint: {{ $style['tint'] }};">
                        <div class="subject-icon"><i class="bi {{ $style['icon'] }}"></i></div>
                        <h3>{{ $category->name }}</h3>
                        <p class="d-none d-lg-block">Jump in and start playing games built for your age group.</p>
                        <span class="go">&rsaquo;</span>
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    <section class="section pt-0">
        <div class="cta">
            <div class="hero-kicker mb-3">No boring bits required</div>
            <h2 class="section-title">Ready to find your next game?</h2>
            <p class="section-intro mx-auto">Start with an age range, then jump into a subject. No complicated setup needed.</p>
            <a href="#subjects" class="btn btn-main btn-lg mt-3">Choose a subject &rarr;</a>
        </div>
    </section>

    <div class="modal fade" id="age-range-required-modal" tabindex="-1" aria-labelledby="age-range-required-modal-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="age-range-required-modal-label">Age range needed</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Please pick an age range before choosing a category.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Okay</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.category-button').forEach((button) => {
            button.addEventListener('click', (event) => {
                if (!document.querySelector('.age-range-button.selected')) {
                    event.preventDefault();

                    bootstrap.Modal.getOrCreateInstance(document.getElementById('age-range-required-modal')).show();
                }
            });
        });

        document.querySelectorAll('.age-range-button').forEach((button) => {
            button.addEventListener('click', (event) => {
                event.preventDefault();

                window.confirmAgeRangeChange(button, async () => {
                    const response = await fetch(button.href, {
                        headers: {
                            Accept: 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (!response.ok) {
                        window.location.href = button.href;
                        return;
                    }

                    document.querySelectorAll('.age-range-button').forEach((ageRangeButton) => {
                        ageRangeButton.classList.toggle('active', ageRangeButton === button);
                        ageRangeButton.classList.toggle('selected', ageRangeButton === button);
                    });
                });
            });
        });
    </script>
@endpush
