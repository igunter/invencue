<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
