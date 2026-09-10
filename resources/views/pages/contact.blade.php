@extends('layouts.app')

@section('meta_title', 'Contact Us - ' . config('app.name'))
@section('meta_blurb', 'Get in touch with the ' . config('app.name') . ' team — ask a question, report a mistake in a game, or share feedback.')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <h1 class="h3 mb-2 text-center">Get in touch</h1>
            <p class="text-secondary text-center mb-4">Got a question, spotted a mistake in one of our games, or just want to say hello? Fill in the form below and we'll get back to you as soon as we can.</p>

            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('contact.send') }}" id="contactForm">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea id="message" class="form-control @error('message') is-invalid @enderror" name="message" rows="5" required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100" id="contactSubmit">
                            <span class="spinner-border spinner-border-sm me-2 d-none" id="contactSpinner" role="status" aria-hidden="true"></span>
                            <span id="contactSubmitLabel">Send message</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            var form = document.getElementById('contactForm');
            if (!form) return;

            form.addEventListener('submit', function () {
                var button = document.getElementById('contactSubmit');
                document.getElementById('contactSpinner').classList.remove('d-none');
                document.getElementById('contactSubmitLabel').textContent = 'Sending…';
                button.disabled = true;
            });
        })();
    </script>
@endsection
