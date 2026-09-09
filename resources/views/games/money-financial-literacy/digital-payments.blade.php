@extends('layouts.app')

@section('meta_title', 'Digital Payments — Money Game for Kids')
@section('meta_blurb', 'A free money game covering digital payments — contactless payments, online banking and digital wallets basics.')
@section('meta_words', 'digital payments game, contactless payments, online banking, digital wallet, kids money game, ks3 money game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-phone',
        'title' => 'Digital Payments',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Digital payment facts'],
        ],
        'aboutTitle' => 'About this digital payments game',
        'aboutText' => 'This free money game covers how digital payments work — contactless payments, online banking, digital wallets and staying safe when paying digitally. Choose your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What is a contactless payment?", a: "Paying by tapping a card or phone instead of entering a PIN" },
                { q: "What is online banking?", a: "Managing your money through a bank's website or app" },
                { q: "What is a digital wallet?", a: "An app that stores your card details so you can pay with your phone or watch" },
                { q: "Why do contactless payments usually have a limit on the amount you can spend?", a: "To help keep payments secure if a card is lost or stolen" },
                { q: "What is a 'direct debit'?", a: "An automatic regular payment set up to pay a bill" },
                { q: "What is a 'standing order'?", a: "A regular fixed payment you set up to send from your account" },
                { q: "Why is it important to keep banking apps and passwords secure?", a: "To stop other people accessing your money" },
                { q: "What is online shopping?", a: "Buying things over the internet instead of in a shop" },
                { q: "What should you check before entering your card details on a website?", a: "That the website is secure and trustworthy" },
                { q: "Why might someone prefer paying by card or phone instead of cash?", a: "It's quick, convenient and you don't need to carry cash" },
                { q: "What is one risk of digital payments if you're not careful?", a: "It can be easy to lose track of how much you're spending" },
                { q: "What does 'two-factor authentication' help protect when banking online?", a: "It adds an extra check to keep your account secure from other people" },
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
                storageKey: 'digitalPaymentsGame.settings',
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
                    return 'Think about how money moves electronically instead of using cash.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You understand digital payments really well!",
            });
        })();
    </script>
@endpush
