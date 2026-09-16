<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RecaptchaVerifier
{
    /**
     * Verify a reCAPTCHA v3 token against Google's siteverify endpoint.
     *
     * reCAPTCHA v3 has no checkbox — it returns a 0.0-1.0 confidence score per
     * request instead of a pass/fail, so we reject anything below a threshold.
     */
    public static function passes(Request $request, string $action, float $minScore = 0.5): bool
    {
        $secret = config('services.recaptcha.secret_key');
        $token = $request->string('g-recaptcha-response')->toString();

        if (!$secret || $token === '') {
            return false;
        }

        $response = Http::asForm()->timeout(5)->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secret,
            'response' => $token,
            'remoteip' => $request->ip(),
        ]);

        return $response->successful()
            && $response->json('success')
            && $response->json('action') === $action
            && $response->json('score', 0) >= $minScore;
    }
}
