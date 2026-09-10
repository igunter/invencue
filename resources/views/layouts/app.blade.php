<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="user-authenticated" content="{{ auth()->check() ? '1' : '0' }}">

        @php
            $siteName = config('app.name', 'Invencue');
            $defaultMetaTitle = $siteName . ' - Free Learning Games & Quizzes for Kids';
            $defaultMetaDescription = 'Play free, interactive learning games and quizzes for kids covering maths, English, science, history and more. Pick an age range and subject — no sign-up needed.';

            $metaTitle = trim($__env->yieldContent('meta_title'));
            if ($metaTitle === '') {
                $plainTitle = trim($__env->yieldContent('title'));
                $metaTitle = $plainTitle !== '' ? $plainTitle . ' - ' . $siteName : $defaultMetaTitle;
            }

            $metaDescription = trim($__env->yieldContent('meta_blurb')) ?: $defaultMetaDescription;
            $metaRobots = trim($__env->yieldContent('robots')) ?: 'index, follow';
            $metaAuthor = trim($__env->yieldContent('author'));
            $canonicalUrl = url()->current();
        @endphp

        <title>{!! $metaTitle !!}</title>
        <meta name="description" content="{!! $metaDescription !!}">
        <meta name="robots" content="{{ $metaRobots }}">
        @if ($metaAuthor !== '')
            <meta name="author" content="{!! $metaAuthor !!}">
        @endif
        <link rel="canonical" href="{{ $canonicalUrl }}">

        <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
        <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" type="image/png">
        <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}">

        <meta property="og:type" content="website">
        <meta property="og:site_name" content="{{ $siteName }}">
        <meta property="og:title" content="{!! $metaTitle !!}">
        <meta property="og:description" content="{!! $metaDescription !!}">
        <meta property="og:url" content="{{ $canonicalUrl }}">
        <meta property="og:image" content="{{ asset('images/favicon.png') }}">

        <meta name="twitter:card" content="summary">
        <meta name="twitter:title" content="{!! $metaTitle !!}">
        <meta name="twitter:description" content="{!! $metaDescription !!}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Space+Grotesk:wght@600;700&display=swap" onload="this.onload=null;this.rel='stylesheet'">
        <link rel="preload" as="style" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" onload="this.onload=null;this.rel='stylesheet'">
        <noscript>
            <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Space+Grotesk:wght@600;700&display=swap">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        </noscript>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ @filemtime(public_path('css/app.css')) ?: '1' }}">

        <script type="application/ld+json">
            {!! json_encode([
                '@context' => 'https://schema.org',
                '@graph' => [
                    [
                        '@type' => 'WebSite',
                        'name' => $siteName,
                        'url' => url('/'),
                    ],
                    [
                        '@type' => 'Organization',
                        'name' => $siteName,
                        'url' => url('/'),
                        'logo' => asset('images/invencue.png'),
                    ],
                ],
            ], JSON_UNESCAPED_SLASHES) !!}
        </script>

        @stack('styles')
        @stack('head')
    </head>
    <body>
        <nav class="navbar navbar-expand-md site-navbar fixed-top">
            <div class="container-xl">
                <a class="navbar-brand brand" href="{{ url('/') }}">
                    <img src="{{ asset('images/invencue.png') }}" alt="{{ config('app.name', 'Invencue') }}" class="brand-logo">
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

        <div class="share-bar py-3">
            <div class="container-xl d-flex flex-column flex-sm-row align-items-center justify-content-center gap-2">
                <div class="d-flex gap-2">
                    <button type="button" class="btn-share" data-share="facebook" aria-label="Share on Facebook" title="Share on Facebook">
                        <i class="bi bi-facebook"></i>
                    </button>
                    <button type="button" class="btn-share" data-share="twitter" aria-label="Share on X" title="Share on X">
                        <i class="bi bi-twitter-x"></i>
                    </button>
                    <button type="button" class="btn-share" data-share="whatsapp" aria-label="Share on WhatsApp" title="Share on WhatsApp">
                        <i class="bi bi-whatsapp"></i>
                    </button>
                    <button type="button" class="btn-share" data-share="linkedin" aria-label="Share on LinkedIn" title="Share on LinkedIn">
                        <i class="bi bi-linkedin"></i>
                    </button>
                    <button type="button" class="btn-share" data-share="instagram" aria-label="Copy link to share on Instagram" title="Copy link to share on Instagram">
                        <i class="bi bi-instagram"></i>
                    </button>
                    <button type="button" class="btn-share" data-share="email" aria-label="Share by email" title="Share by email">
                        <i class="bi bi-envelope"></i>
                    </button>
                    <button type="button" class="btn-share" data-share="copy" aria-label="Copy link" title="Copy link">
                        <i class="bi bi-clipboard"></i>
                    </button>
                </div>
            </div>
        </div>

        <footer class="site-footer py-4 mt-auto position-relative">
            <div class="container-xl d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 text-center text-md-start small">
                <div class="d-flex flex-column flex-md-row align-items-center gap-2">
                    <img src="{{ asset('images/invencue.png') }}" alt="{{ config('app.name', 'Invencue') }}" class="footer-logo">
                    <span>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. Learn by playing.</span>
                </div>
                <nav class="footer-links d-flex gap-3">
                    <a href="{{ route('faq') }}">FAQs</a>
                    <a href="{{ route('contact.show') }}">Contact</a>
                    <a href="{{ route('terms') }}">Terms</a>
                    <a href="{{ route('privacy') }}">Privacy</a>
                </nav>
                <div class="footer-stars">⭐️✨⭐️</div>
            </div>
        </footer>

        @include('partials.cookie-consent')

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="{{ asset('js/game-results.js') }}"></script>
        <script>
            (function () {
                var navbar = document.querySelector('.site-navbar');
                if (!navbar) return;
                var toggle = function () {
                    var isScrolled = window.scrollY > 20;
                    navbar.classList.toggle('is-scrolled', isScrolled);
                    document.body.classList.toggle('is-scrolled', isScrolled);
                };
                toggle();
                window.addEventListener('scroll', toggle, { passive: true });

                var updateNavbarHeight = function () {
                    document.documentElement.style.setProperty('--navbar-h', navbar.offsetHeight + 'px');
                };
                updateNavbarHeight();
                if (window.ResizeObserver) {
                    new ResizeObserver(updateNavbarHeight).observe(navbar);
                } else {
                    navbar.addEventListener('transitionend', updateNavbarHeight);
                    window.addEventListener('resize', updateNavbarHeight);
                }
            })();
        </script>

        <script>
            (function () {
                var buttons = document.querySelectorAll('.btn-share');
                if (!buttons.length) return;

                var pageUrl = window.location.href;
                var pageTitle = document.title;

                var shareUrls = {
                    facebook: 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(pageUrl),
                    twitter: 'https://twitter.com/intent/tweet?url=' + encodeURIComponent(pageUrl) + '&text=' + encodeURIComponent(pageTitle),
                    whatsapp: 'https://wa.me/?text=' + encodeURIComponent(pageTitle + ' ' + pageUrl),
                    linkedin: 'https://www.linkedin.com/sharing/share-offsite/?url=' + encodeURIComponent(pageUrl),
                    email: 'mailto:?subject=' + encodeURIComponent(pageTitle) + '&body=' + encodeURIComponent(pageUrl),
                };

                var copyLink = function (button, copiedTitle) {
                    var icon = button.querySelector('i');
                    var originalIcon = icon.className;
                    var originalTitle = button.getAttribute('title');
                    var restore = function () {
                        icon.className = originalIcon;
                        button.classList.remove('is-copied');
                        button.setAttribute('title', originalTitle);
                    };
                    navigator.clipboard.writeText(pageUrl).then(function () {
                        icon.className = 'bi bi-check-lg';
                        button.classList.add('is-copied');
                        button.setAttribute('title', copiedTitle || 'Copied!');
                        setTimeout(restore, 1500);
                    });
                };

                buttons.forEach(function (button) {
                    button.addEventListener('click', function () {
                        var type = button.getAttribute('data-share');

                        if (type === 'copy') {
                            copyLink(button, 'Copied!');
                            return;
                        }

                        if (type === 'instagram') {
                            copyLink(button, 'Link copied — paste it into Instagram');
                            return;
                        }

                        if (type === 'email') {
                            window.location.href = shareUrls.email;
                            return;
                        }

                        window.open(shareUrls[type], '_blank', 'noopener,noreferrer,width=600,height=500');
                    });
                });
            })();
        </script>

        @include('partials.age-range-confirm-modal')

        @stack('scripts')
    </body>
</html>
