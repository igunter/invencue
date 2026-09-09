@extends('layouts.app')

@section('meta_title', 'Staying Safe Online — Computing Game for Kids')
@section('meta_blurb', 'A free computing game for kids — learn simple e-safety rules like never sharing passwords and telling a trusted adult if something feels wrong.')
@section('meta_words', 'staying safe online game, e-safety game for kids, internet safety quiz, digital safety ks1 ks2')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-shield-lock',
        'title' => 'Staying Safe Online',
        'subtitle' => 'Read the situation, then pick the safe choice!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Online safety'],
        ],
        'aboutTitle' => 'About this staying safe online game',
        'aboutText' => 'This free computing game teaches young kids simple, important e-safety rules — never sharing passwords, keeping personal information private, and always telling a trusted adult if something online feels wrong.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "If a website asks for your password, what should you do?", a: "Never share your password, even with people you think you know" },
                { q: "If something online makes you feel worried or uncomfortable, what should you do?", a: "Tell a trusted adult straight away" },
                { q: "Is it safe to share your home address with someone you only know from a game or app?", a: "No, never share personal information like your address online" },
                { q: "What should you do if a stranger online asks to meet you in person?", a: "Say no and tell a trusted adult" },
                { q: "What is a strong password more likely to include?", a: "A mix of letters, numbers and symbols that's hard to guess" },
                { q: "Should you click on a pop-up that says you've won a prize?", a: "No, ignore it and tell an adult — it could be a trick" },
                { q: "What should you do before sharing a photo of yourself online?", a: "Ask a trusted adult first" },
                { q: "If someone online is being unkind to you, what should you do?", a: "Don't reply — tell a trusted adult and block them if you can" },
                { q: "Should you download an app or game without asking an adult first?", a: "No, always ask a trusted adult first" },
                { q: "What does it mean to 'log out' when you finish using a shared computer?", a: "It signs you out so other people can't see your account" },
                { q: "Why shouldn't you share your full name and school with people you meet online?", a: "It could help a stranger find you in real life" },
                { q: "What is the golden rule for anything that feels wrong online?", a: "Tell a trusted adult" },
            ];

            function randInt(min, max) {
                return Math.floor(Math.random() * (max - min + 1)) + min;
            }

            function shuffle(arr) {
                const out = arr.slice();
                for (let i = out.length - 1; i > 0; i--) {
                    const j = randInt(0, i);
                    [out[i], out[j]] = [out[j], out[i]];
                }
                return out;
            }

            window.ScienceQuiz.run({
                storageKey: 'stayingSafeOnlineGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const item = FACTS[randInt(0, FACTS.length - 1)];
                    return { category: type, correctText: item.a, questionText: item.q };
                },

                buildChoices: function(q) {
                    const distractors = shuffle(
                        FACTS.map(function(f) { return f.a; }).filter(function(a) { return a !== q.correctText; })
                    ).slice(0, 3);
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about what a sensible, careful person would do to stay safe.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're an online safety superstar!",
            });
        })();
    </script>
@endpush
