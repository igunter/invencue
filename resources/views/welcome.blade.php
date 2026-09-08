@extends('layouts.app')

@section('content')
    <div class="p-5 mb-4 bg-body-tertiary rounded-3 text-center">
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
    <div class="p-5 mb-4 bg-body-tertiary rounded-3 text-center">
        @foreach ($age_ranges as $age_range)
            <a href="{{ route('age-range.select', ['age_range' => $age_range]) }}" class="btn btn-outline-secondary btn-lg m-1 age-range-button{{ $selected_age_range === $age_range ? ' selected' : '' }}">
                {{ $age_range }}
            </a>
        @endforeach
    </div>

    {{-- Category selector --}}
    <div class="p-5 mb-4 bg-body-tertiary rounded-3 text-center">
        <div class="row row-cols-3 justify-content-center g-2">
            @foreach ($categories as $category)
                <div class="col d-flex justify-content-center">
                    <a href="{{ route('category.show', $category->slug) }}" class="btn btn-outline-secondary btn-lg">
                        {{ $category->name }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.age-range-button').forEach((button) => {
            button.addEventListener('click', async (event) => {
                event.preventDefault();

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
    </script>
@endpush
