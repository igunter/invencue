<?php

namespace App\Http\Controllers;

use App\Mail\AccountChangedMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validateWithBag('updateInfo', [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'current_password' => ['required', 'current_password'],
        ]);

        $emailChanged = $validated['email'] !== $user->email;
        $oldEmail = $user->email;

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($emailChanged) {
            Mail::to($oldEmail)->send(new AccountChangedMail($user, 'email', oldEmail: $oldEmail, newEmail: $user->email));
            $user->sendEmailVerificationNotification();

            return redirect()->route('profile.edit')
                ->with('status', 'Your details have been updated. Since you changed your email, please verify your new address — we\'ve sent a confirmation to '.$oldEmail.' about this change too.');
        }

        return redirect()->route('profile.edit')->with('status', 'Your details have been updated.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update(['password' => Hash::make($validated['password'])]);

        Mail::to($user->email)->send(new AccountChangedMail($user, 'password'));

        return redirect()->route('profile.edit')->with('status', 'Your password has been changed.');
    }
}
