<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    // TODO: replace with the real address this should be sent to.
    private const RECIPIENT_EMAIL = 'REPLACE_ME@example.com';

    public function show(): View
    {
        return view('pages.contact');
    }

    public function send(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        Mail::to(self::RECIPIENT_EMAIL)->send(new ContactMessageMail(
            $validated['name'],
            $validated['email'],
            $validated['message'],
        ));

        return redirect()->route('contact.show')->with('status', "Thanks — your message has been sent. We'll get back to you soon.");
    }
}
