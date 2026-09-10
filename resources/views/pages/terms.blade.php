@extends('layouts.app')

@section('meta_title', 'Terms & Conditions - ' . config('app.name'))
@section('meta_blurb', 'Read the terms and conditions for using ' . config('app.name') . ', our free educational games site for kids.')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4 p-md-5">
                    <h1 class="h3 mb-1">Terms &amp; Conditions</h1>
                    <p class="text-secondary small mb-4">Last updated: {{ now()->format('d F Y') }}</p>

                    <p>Welcome to {{ config('app.name') }}. These terms and conditions explain the rules for using our website and games. By using {{ config('app.name') }}, you agree to these terms.</p>

                    <h2 class="h5 mt-4">1. Using {{ config('app.name') }}</h2>
                    <p>{{ config('app.name') }} provides free educational quiz games covering a range of school subjects. You can play most games without creating an account. Creating an account lets you save your scores and track your progress over time.</p>
                    <p>{{ config('app.name') }} is intended for use by school-age children, typically with the involvement or permission of a parent, guardian or teacher where an account is created.</p>

                    <h2 class="h5 mt-4">2. Accounts</h2>
                    <p>If you create an account, you're responsible for keeping your password secure and for any activity that happens under your account. You must provide accurate information when registering, including a genuine date of birth, so we can show you age-appropriate content.</p>
                    <p>You can update your account details, change your password, or ask us to delete your account at any time — see our <a href="{{ route('privacy') }}">Privacy Policy</a> for more on how we handle your data.</p>

                    <h2 class="h5 mt-4">3. Acceptable use</h2>
                    <p>Please use {{ config('app.name') }} sensibly and don't try to disrupt the site, access other users' accounts, or use the site for anything unlawful or harmful.</p>

                    <h2 class="h5 mt-4">4. Content</h2>
                    <p>We aim to keep the facts in our games accurate and up to date, but {{ config('app.name') }} is provided for educational and entertainment purposes and shouldn't be relied on as the sole source for schoolwork, exams or professional advice. If you spot something that looks wrong, please <a href="{{ route('contact.show') }}">let us know</a>.</p>

                    <h2 class="h5 mt-4">5. Availability</h2>
                    <p>We try to keep {{ config('app.name') }} available and working correctly, but we don't guarantee the site will always be available, uninterrupted, or error-free.</p>

                    <h2 class="h5 mt-4">6. Changes to these terms</h2>
                    <p>We may update these terms from time to time. If we make significant changes, we'll update the "last updated" date at the top of this page.</p>

                    <h2 class="h5 mt-4">7. Contact us</h2>
                    <p>If you have any questions about these terms, please <a href="{{ route('contact.show') }}">get in touch</a>.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
