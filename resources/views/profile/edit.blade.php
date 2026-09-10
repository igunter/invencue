@extends('layouts.app')

@section('meta_title', 'My Account - ' . config('app.name'))
@section('robots', 'noindex, nofollow')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <h1 class="h4 mb-4">My account</h1>

            <div class="card mb-4">
                <div class="card-body p-4">
                    <h2 class="h6 text-uppercase text-secondary mb-3">Account details</h2>

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
                            <div class="form-text">Changing your email will require you to verify the new address, and we'll email your current address ({{ $user->email }}) to let you know.</div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="details_current_password" class="form-label">Current password</label>
                            <input id="details_current_password" type="password" class="form-control @error('current_password', 'updateInfo') is-invalid @enderror" name="current_password" required autocomplete="current-password">
                            <div class="form-text">Enter your current password to confirm these changes.</div>
                            @error('current_password', 'updateInfo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Save details</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body p-4">
                    <h2 class="h6 text-uppercase text-secondary mb-3">Change password</h2>

                    <form method="POST" action="{{ route('profile.password') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="password_current_password" class="form-label">Current password</label>
                            <input id="password_current_password" type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" name="current_password" required autocomplete="current-password">
                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">New password</label>
                            <input id="password" type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" name="password" required autocomplete="new-password">
                            @error('password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm new password</label>
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <div class="form-text mb-3">We'll email {{ $user->email }} to let you know your password was changed.</div>

                        <button type="submit" class="btn btn-primary">Change password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
