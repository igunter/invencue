@extends('layouts.app')

@section('meta_title', 'Register - ' . config('app.name'))
@section('robots', 'noindex, nofollow')

@push('head')
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card">
                <div class="card-body p-4">
                    <h1 class="h4 mb-4 text-center">Create an account</h1>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="username">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="date_of_birth" class="form-label">Date of birth</label>
                            <input id="date_of_birth" type="date" class="form-control @error('date_of_birth') is-invalid @enderror" name="date_of_birth" value="{{ old('date_of_birth') }}" required
                                   max="{{ now()->subYears(4)->toDateString() }}" min="1900-01-01">
                            <div class="form-text">Used to put you in the right age range — you can change your age range later.</div>
                            @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm password</label>
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <input type="hidden" name="g-recaptcha-response" id="recaptchaResponse">
                        @error('g-recaptcha-response')
                            <div class="text-danger small mt-2 mb-3">{{ $message }}</div>
                        @enderror

                        <button type="submit" class="btn btn-primary w-100" id="registerSubmit">
                            <span class="spinner-border spinner-border-sm me-2 d-none" id="registerSpinner" role="status" aria-hidden="true"></span>
                            <span id="registerSubmitLabel">Register</span>
                        </button>

                        <div class="text-center mt-3 small">
                            <a href="{{ route('login') }}">Already have an account?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            var form = document.querySelector('form[action="{{ route('register') }}"]');
            if (!form) return;

            var siteKey = @json(config('services.recaptcha.site_key'));

            form.addEventListener('submit', function (event) {
                event.preventDefault();

                var button = document.getElementById('registerSubmit');
                document.getElementById('registerSpinner').classList.remove('d-none');
                document.getElementById('registerSubmitLabel').textContent = 'Registering…';
                button.disabled = true;

                grecaptcha.ready(function () {
                    grecaptcha.execute(siteKey, { action: 'register' }).then(function (token) {
                        document.getElementById('recaptchaResponse').value = token;
                        form.submit();
                    });
                });
            });
        })();
    </script>
@endsection
