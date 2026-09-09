@extends('layouts.app')

@section('content')
    <div class="p-3 mb-4 bg-body-tertiary rounded-3 text-center">
        <h1 class="display-5 fw-bold">{{ config('app.name', 'Laravel') }}</h1>
        <p class="col-lg-8 mx-auto fs-5">
            A Laravel starter kit with Bootstrap, authentication, and everything else you need to get moving.
        </p>
        @guest
            <div class="d-flex justify-content-center gap-2">
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-person-plus me-1"></i>Get started
                </a>
                <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="bi bi-box-arrow-in-right me-1"></i>Log in
                </a>
            </div>
        @else
            <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-speedometer2 me-1"></i>Go to dashboard
            </a>
        @endguest
    </div>

    {{-- Age range selection buttons --}}
    <div class="p-3 mb-4 bg-body-tertiary rounded-3 text-center">
        @foreach ($age_ranges as $age_range)
            <a href="{{ route('age-range.select', ['age_range' => $age_range->slug]) }}" class="btn btn-outline-secondary btn-lg m-2 age-range-button{{ $selected_age_range === $age_range->slug ? ' selected' : '' }}">
                {{ $age_range->name }}
            </a>
        @endforeach
    </div>

    {{-- Category selector --}}
    @php
        $categoryStyles = [
            'maths'       => ['icon' => 'bi-calculator',        'accent' => '#1c7ed6', 'tint' => '#e7f5ff'],
            'english'     => ['icon' => 'bi-book',              'accent' => '#9c36b5', 'tint' => '#f8f0fc'],
            'biology'     => ['icon' => 'bi-diagram-2',         'accent' => '#2f9e44', 'tint' => '#ebfbee'],
            'chemistry'   => ['icon' => 'bi-droplet-half',      'accent' => '#0c8599', 'tint' => '#e3fafc'],
            'physics'     => ['icon' => 'bi-lightning-charge',  'accent' => '#e8590c', 'tint' => '#fff4e6'],
            'earth-space' => ['icon' => 'bi-globe2',            'accent' => '#3b5bdb', 'tint' => '#edf2ff'],
            'science-lab' => ['icon' => 'bi-clipboard',         'accent' => '#d6336c', 'tint' => '#fff0f6'],
            'history'     => ['icon' => 'bi-hourglass-split',   'accent' => '#e67700', 'tint' => '#fff9db'],
            'geography'   => ['icon' => 'bi-map',               'accent' => '#0ca678', 'tint' => '#e6fcf5'],
        ];
        $defaultStyle = ['icon' => 'bi-grid', 'accent' => '#495057', 'tint' => '#f8f9fa'];

        $visibleCategories = $categories->filter(function ($category) use ($selected_age_range) {
            return $category->games()
                ->where('is_active', true)
                ->when($selected_age_range, fn ($query) => $query->where('age_range_slug', $selected_age_range))
                ->exists();
        });
    @endphp
    <div class="p-3 mb-4 bg-body-tertiary rounded-3 text-center">
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 g-3 justify-content-center">
            @foreach ($visibleCategories as $category)
                @php $style = $categoryStyles[$category->slug] ?? $defaultStyle; @endphp
                <div class="col">
                    <a href="{{ route('category.show', $category->slug) }}" class="category-card-link category-button">
                        <div class="card category-card h-100" style="--cat-accent: {{ $style['accent'] }}; --cat-tint: {{ $style['tint'] }};">
                            <div class="card-body text-center">
                                <div class="category-icon"><i class="bi {{ $style['icon'] }}"></i></div>
                                <div class="fw-bold">{{ $category->name }}</div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

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
                        ageRangeButton.classList.toggle('selected', ageRangeButton === button);
                    });
                });
            });
        });
    </script>
@endpush
