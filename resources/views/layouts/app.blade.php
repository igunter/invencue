<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Laravel'))</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Space+Grotesk:wght@600;700&display=swap">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ @filemtime(public_path('css/app.css')) ?: '1' }}">

        @stack('styles')
        @stack('head')
    </head>
    <body>
        <nav class="navbar navbar-expand-md site-navbar fixed-top">
            <div class="container-xl">
                <a class="navbar-brand brand" href="{{ url('/') }}">
                    <svg class="brand-mark" viewBox="0 0 64 64" aria-hidden="true">
                        <defs>
                            <linearGradient id="logoGradient" x1="0" x2="1" y1="0" y2="1">
                                <stop offset="0" stop-color="#4e7cff"/>
                                <stop offset=".55" stop-color="#8a5cff"/>
                                <stop offset="1" stop-color="#ff6fae"/>
                            </linearGradient>
                        </defs>
                        <path fill="url(#logoGradient)" d="M13 8h38a7 7 0 0 1 7 7v34a7 7 0 0 1-7 7H13a7 7 0 0 1-7-7V15a7 7 0 0 1 7-7Z"/>
                        <path d="M19 21h8v22h-8zm14 0h12v6H33zm0 10h12v6H33zm0 10h8v2h-8z" fill="#fff" opacity=".96"/>
                        <circle cx="48" cy="43" r="4" fill="#ffc83d"/>
                    </svg>
                    <span class="brand-word">{{ config('app.name', 'Laravel') }}</span>
                </a>

                <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarOffcanvas" aria-controls="navbarOffcanvas" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                @include('partials.nav-links-md')
            </div>
        </nav>

        @include('partials.nav-links-sm')

        <main class="container-xl">
            <x-flash-messages />

            @yield('content')
        </main>

        <footer class="site-footer py-4 mt-auto">
            <div class="container-xl d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 text-center text-md-start small">
                <div>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. Learn by playing.</div>
                <div class="footer-stars">⭐️✨⭐️</div>
            </div>
        </footer>

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        @include('partials.age-range-confirm-modal')

        @stack('scripts')
    </body>
</html>
