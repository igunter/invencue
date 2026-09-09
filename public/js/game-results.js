/**
 * Fire-and-forget results saver used by every quiz game — both the shared
 * science-quiz.js engine and the standalone maths game scripts call
 * window.InvencueResults.submit() when a game finishes. No-ops silently for
 * guests, and never throws, so it can't break the quiz UI if it fails.
 */
window.InvencueResults = (function () {
    function isAuthenticated() {
        var meta = document.querySelector('meta[name="user-authenticated"]');
        return !!meta && meta.getAttribute('content') === '1';
    }

    function submit(categorySlug, gameSlug, score, total) {
        if (!isAuthenticated()) return;

        var tokenMeta = document.querySelector('meta[name="csrf-token"]');
        if (!tokenMeta) return;

        try {
            fetch('/game-results', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': tokenMeta.getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    category_slug: categorySlug,
                    game_slug: gameSlug,
                    score: score,
                    total: total,
                }),
            }).catch(function () {});
        } catch (e) {
            // never let a results-saving failure break the quiz UI
        }
    }

    return { submit: submit };
})();
