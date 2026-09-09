@extends('layouts.app')

@section('meta_title', 'Coins, Notes & Value — Money Game for Kids')
@section('meta_blurb', 'A free money game for young kids about what money is and represents — coins, notes and value.')
@section('meta_words', 'coins and notes game, what is money, kids money game, financial literacy for kids, ks1 money game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-cash-coin',
        'title' => 'Coins, Notes & Value',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Money facts'],
        ],
        'aboutTitle' => 'About this coins, notes & value game',
        'aboutText' => 'This free money game covers what money is and what it represents — coins, notes, currency and value — without any counting or arithmetic. Choose your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What is money used for?", a: "To pay for things we need and want" },
                { q: "What are the two main types of physical money in the UK?", a: "Coins and notes (banknotes)" },
                { q: "Why are coins usually used for smaller amounts and notes for larger ones?", a: "Coins are for smaller values, notes for bigger values, so it's easier to carry" },
                { q: "What does the value of a coin or note tell you?", a: "How much it is worth" },
                { q: "What is it called when you use a card instead of coins or notes to pay?", a: "Paying digitally / by card" },
                { q: "Why do shops and banks need to be able to trust that money is real?", a: "Fake money isn't worth anything and it's against the law to make it" },
                { q: "What is 'currency'?", a: "The type of money used in a country, like pounds in the UK" },
                { q: "Why can't you just draw your own money and use it?", a: "Only official money made by the government/bank is real and worth anything" },
                { q: "What do we call the money used in the United Kingdom?", a: "Pounds sterling (£)" },
                { q: "Besides coins and notes, how else can people pay for things today?", a: "Cards, phones and other digital payments" },
                { q: "Why is it useful to have money instead of swapping goods directly?", a: "It makes buying and selling things much easier and fairer" },
                { q: "What should you check when someone gives you change, to make sure it's correct?", a: "Count the coins and notes you're given" },
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
                storageKey: 'coinsNotesAndValueGame.settings',
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
                    return 'Think about what money is for, not about counting it.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You understand money really well!",
            });
        })();
    </script>
@endpush
