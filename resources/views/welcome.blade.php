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
        $categoryIcons = [
            'maths'       => 'bi-calculator',
            'english'     => 'bi-book',
            'biology'     => 'bi-diagram-2',
            'chemistry'   => 'bi-droplet-half',
            'physics'     => 'bi-lightning-charge',
            'earth-space' => 'bi-globe2',
            'science-lab' => 'bi-clipboard',
            'history'     => 'bi-hourglass-split',
            'geography'   => 'bi-map',
        ];
    @endphp
    <div class="p-3 mb-4 bg-body-tertiary rounded-3 text-center">
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 g-3 justify-content-center">
            @foreach ($categories as $category)
                <div class="col">
                    <a href="{{ route('category.show', $category->slug) }}" class="category-card-link category-button">
                        <div class="card category-card h-100">
                            <div class="card-body text-center">
                                <div class="category-icon"><i class="bi {{ $categoryIcons[$category->slug] ?? 'bi-grid' }}"></i></div>
                                <div class="fw-semibold">{{ $category->name }}</div>
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
