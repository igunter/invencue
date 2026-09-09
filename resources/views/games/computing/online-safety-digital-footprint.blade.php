@extends('layouts.app')

@section('meta_title', 'Online Safety & Digital Footprint — Computing Game for Kids')
@section('meta_blurb', 'A free computing game — learn about passwords, digital footprints and privacy settings.')
@section('meta_words', 'online safety game, digital footprint quiz, privacy settings game, ks3 computing game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-fingerprint',
        'title' => 'Online Safety & Digital Footprint',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Online safety'],
        ],
        'aboutTitle' => 'About this online safety & digital footprint game',
        'aboutText' => 'This free computing game covers online safety at KS3 level — strong passwords, privacy settings, cyberbullying, and understanding the digital footprint we all leave behind online.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What is the trail of information left behind by everything you do online called?", a: "Your digital footprint" },
                { q: "What is a secret combination of letters, numbers and symbols used to protect an account called?", a: "A password" },
                { q: "What makes a password strong?", a: "A long mix of letters, numbers and symbols that's hard to guess" },
                { q: "What are settings that control who can see your posts and information online called?", a: "Privacy settings" },
                { q: "What do we call it when someone pretends to be someone else online to trick people?", a: "Catfishing (identity deception)" },
                { q: "What is it called when someone is repeatedly unkind or threatening to another person online?", a: "Cyberbullying" },
                { q: "Why shouldn't you use the same password for every account?", a: "If one account is hacked, all your accounts become at risk" },
                { q: "What is a security method that asks for a password plus a code sent to your phone called?", a: "Two-factor authentication (2FA)" },
                { q: "What should you check about a website before entering personal details, like the address starting with https?", a: "Whether the connection is secure" },
                { q: "Once you post a photo or comment online, why is it hard to fully remove it?", a: "Other people may have already copied, shared or saved it" },
                { q: "What should you do if you're unsure whether a message or link is safe?", a: "Don't click it, and tell a trusted adult" },
                { q: "What term describes the personal information companies collect about you as you browse?", a: "Data (personal data)" },
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
                storageKey: 'onlineSafetyDigitalFootprintGame.settings',
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
                    return 'Think about what keeps your accounts, information and reputation safe online.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're an online safety superstar!",
            });
        })();
    </script>
@endpush
