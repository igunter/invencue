<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'date_of_birth' => [
                'required',
                'date',
                'after:'.now()->subYears(20)->toDateString(),
                'before_or_equal:'.now()->subYears(4)->toDateString(),
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $age = Carbon::parse($request->date_of_birth)->age;
        $ageRange = match (true) {
            $age < 10 => '6-10',
            $age < 13 => '10-13',
            default => '13-16',
        };

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'date_of_birth' => $request->date_of_birth,
            'age_range' => $ageRange,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard'));
    }
}
