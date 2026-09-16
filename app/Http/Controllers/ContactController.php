<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('pages.contact');
    }

    public function send(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', Rule::email()->rfcCompliant()],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $captchaToken = $request->string('g-recaptcha-response')->toString();
        $captchaResponse = $captchaToken !== '' && config('services.recaptcha.secret_key')
            ? Http::asForm()->timeout(5)->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $captchaToken,
                'remoteip' => $request->ip(),
            ])
            : null;

        // reCAPTCHA v3 has no checkbox — it returns a 0.0-1.0 confidence score per
        // request instead of a pass/fail, so we reject anything below a threshold.
        $isHuman = $captchaResponse?->successful()
            && $captchaResponse->json('success')
            && $captchaResponse->json('action') === 'contact'
            && $captchaResponse->json('score', 0) >= 0.5;

        if (!$isHuman) {
            return redirect()->route('contact.show')
                ->withErrors(['g-recaptcha-response' => 'Please complete the anti-spam check and try again.'])
                ->withInput();
        }

        try {
            Mail::to('hello@invencue.com')->send(new ContactMessageMail(
                $validated['name'],
                $validated['email'],
                $validated['message'],
            ));

            Log::info('Contact form email sent.', ['from' => $validated['email']]);
        } catch (Throwable $e) {
            Log::error('Contact form email failed to send.', [
                'from' => $validated['email'],
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('contact.show')->with('status', "Sorry, something went wrong sending your message. Please try again later.");
        }

        return redirect()->route('contact.show')->with('status', "Thanks — your message has been sent. We'll get back to you soon.");
    }
}
