<div id="cookieConsentBanner" class="cookie-consent-banner d-none">
    <div class="container-xl d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 py-3">
        <p class="mb-0 small">
            We use a small number of essential cookies to keep {{ config('app.name') }} working (like remembering your chosen age range) and to remember your preferences. See our <a href="{{ route('privacy') }}">Privacy Policy</a> for details.
        </p>
        <div class="d-flex gap-2 flex-shrink-0">
            <button type="button" class="btn btn-primary btn-sm" id="cookieConsentAccept">Got it</button>
        </div>
    </div>
</div>

<script>
    (function() {
        var STORAGE_KEY = 'cookieConsent.accepted';
        var banner = document.getElementById('cookieConsentBanner');
        var acceptBtn = document.getElementById('cookieConsentAccept');
        if (!banner || !acceptBtn) return;

        var alreadyAccepted = false;
        try {
            alreadyAccepted = localStorage.getItem(STORAGE_KEY) === '1';
        } catch (e) {
            // localStorage unavailable — just don't show the banner rather than error
            alreadyAccepted = true;
        }

        if (!alreadyAccepted) {
            banner.classList.remove('d-none');
        }

        acceptBtn.addEventListener('click', function() {
            banner.classList.add('d-none');
            try {
                localStorage.setItem(STORAGE_KEY, '1');
            } catch (e) {
                // ignore — banner will just reappear next visit
            }
        });
    })();
</script>
