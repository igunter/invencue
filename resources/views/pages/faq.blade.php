@extends('layouts.app')

@section('title', 'FAQs - ' . config('app.name'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="h3 mb-4 text-center">Frequently asked questions</h1>

            <div class="accordion" id="faqAccordion">
                @php
                    $faqs = [
                        [
                            'q' => 'Is ' . config('app.name') . ' free to use?',
                            'a' => 'Yes — every game is completely free to play, with or without an account.',
                        ],
                        [
                            'q' => 'Do I need to create an account to play?',
                            'a' => "No. You can pick an age range and jump straight into any game as a guest. Creating a free account just lets you save your scores and see your progress over time on your dashboard.",
                        ],
                        [
                            'q' => 'How does the age range work?',
                            'a' => "Choosing an age range (6-10, 10-13 or 13-16) shows you games pitched at the right level. If you register, your age range is set from your date of birth, but you can change it at any time — your choice is remembered either in your account or in a cookie if you're not signed in.",
                        ],
                        [
                            'q' => 'What subjects are covered?',
                            'a' => "Maths, English, Biology, Chemistry, Physics, Earth & Space, Science Lab, History, Geography, Psychology, Computing, Art & Design, Music, Religious Education and Money & Financial Literacy — use the Subjects menu at the top of the page to browse them all.",
                        ],
                        [
                            'q' => 'How is my score calculated?',
                            'a' => "Each game shows your score out of the number of questions you answered. If you have an account, your results are saved so you can see your accuracy over time and how you compare to other players in your age range.",
                        ],
                        [
                            'q' => "I found a mistake in one of the games — what should I do?",
                            'a' => "Please let us know! Use the Contact page to tell us which game and question, and we'll take a look.",
                        ],
                        [
                            'q' => 'How do I change my email or password?',
                            'a' => 'Once logged in, go to "My account" from the menu to update your name, email or password. If you change your email or password, we\'ll email your previous address to let you know, in case it wasn\'t you.',
                        ],
                        [
                            'q' => 'How do I delete my account?',
                            'a' => 'Get in touch via the Contact page and we\'ll delete your account and associated data for you.',
                        ],
                        [
                            'q' => 'Do you use cookies?',
                            'a' => 'We use a small number of essential and preference cookies — see our <a href="' . route('privacy') . '">Privacy Policy</a> for details.',
                        ],
                    ];
                @endphp

                @foreach ($faqs as $index => $faq)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqHeading{{ $index }}">
                            <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse{{ $index }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="faqCollapse{{ $index }}">
                                {{ $faq['q'] }}
                            </button>
                        </h2>
                        <div id="faqCollapse{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="faqHeading{{ $index }}" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                {!! $faq['a'] !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <p class="text-center text-secondary mt-4">Can't find what you're looking for? <a href="{{ route('contact.show') }}">Get in touch</a>.</p>
        </div>
    </div>
@endsection
