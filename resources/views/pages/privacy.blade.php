@extends('layouts.app')

@section('title', 'Privacy Policy - ' . config('app.name'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4 p-md-5">
                    <h1 class="h3 mb-1">Privacy Policy</h1>
                    <p class="text-secondary small mb-4">Last updated: {{ now()->format('d F Y') }}</p>

                    <p>This policy explains what information {{ config('app.name') }} collects, why, and how it's used. We try to collect as little personal information as possible.</p>

                    <h2 class="h5 mt-4">1. Playing without an account</h2>
                    <p>You can play any game on {{ config('app.name') }} without creating an account. We don't require any personal information to play. A small cookie remembers your chosen age range so we can show you suitable games — see the "Cookies" section below.</p>

                    <h2 class="h5 mt-4">2. Information we collect if you register</h2>
                    <p>If you choose to create an account, we collect:</p>
                    <ul>
                        <li>Your name and email address</li>
                        <li>Your date of birth, used to set your initial age range</li>
                        <li>A securely hashed password (we never store your actual password)</li>
                        <li>Your quiz results (which games you've played, your scores and when), so we can show you your progress</li>
                    </ul>
                    <p>We use your email address to verify your account and to let you know about important changes to your account, such as your email or password being changed.</p>

                    <h2 class="h5 mt-4">3. How we use your information</h2>
                    <p>We use account information to run your account, show you your own progress, and — where relevant — compare your results anonymously against other users in your age range. We don't sell your personal information to third parties.</p>

                    <h2 class="h5 mt-4">4. Cookies</h2>
                    <p>We use a small number of cookies:</p>
                    <ul>
                        <li><strong>Essential cookies</strong> — needed for the site to work, such as keeping you logged in and remembering your chosen age range. These can't be switched off.</li>
                        <li><strong>Preference cookies</strong> — remember choices like whether you've dismissed the cookie notice.</li>
                    </ul>
                    <p>We don't currently use advertising or third-party tracking cookies.</p>

                    <h2 class="h5 mt-4">5. How long we keep your information</h2>
                    <p>We keep your account information for as long as your account is active. If you'd like your account and data deleted, please <a href="{{ route('contact.show') }}">contact us</a>.</p>

                    <h2 class="h5 mt-4">6. Your rights</h2>
                    <p>You can view and update your account details at any time from your account page. You can also ask us to tell you what information we hold about you, correct it, or delete it, by <a href="{{ route('contact.show') }}">contacting us</a>.</p>

                    <h2 class="h5 mt-4">7. Children's privacy</h2>
                    <p>{{ config('app.name') }} is designed to be used by school-age children. Where a child creates an account, we encourage parents, guardians or teachers to be involved. We only collect the information described above and do not knowingly collect more than is needed to run the service.</p>

                    <h2 class="h5 mt-4">8. Changes to this policy</h2>
                    <p>We may update this policy from time to time. If we make significant changes, we'll update the "last updated" date at the top of this page.</p>

                    <h2 class="h5 mt-4">9. Contact us</h2>
                    <p>If you have any questions about this policy or how we handle your information, please <a href="{{ route('contact.show') }}">get in touch</a>.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
