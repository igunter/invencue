@extends('layouts.app')

@section('meta_title', 'Spending Wisely — Money Game for Kids')
@section('meta_blurb', 'A free money game for young kids about making sensible choices when spending money.')
@section('meta_words', 'spending wisely game, kids money game, smart spending for kids, financial literacy for kids, ks1 money game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-cart-check',
        'title' => 'Spending Wisely',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Spending facts'],
        ],
        'aboutTitle' => 'About this spending wisely game',
        'aboutText' => 'This free money game helps young kids learn how to make sensible choices about spending — thinking before buying, comparing prices, and not spending everything at once. Choose your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "Before buying something, what's a wise first question to ask?", a: "Do I really need this, or do I just want it?" },
                { q: "What does it mean to 'spend wisely'?", a: "To think carefully before buying something" },
                { q: "If you spend all your money on one thing, what happens?", a: "You won't have money left for anything else" },
                { q: "What is it called when you wait before buying something to make sure you really want it?", a: "Thinking it over / waiting" },
                { q: "Why might it be smart to compare prices in two shops before buying?", a: "To find the best price and not overspend" },
                { q: "What should you check before buying something, to make sure you can afford it?", a: "How much money you have" },
                { q: "What's a good habit when you have some money to spend?", a: "Plan what you'll spend it on instead of spending it straight away" },
                { q: "If a toy looks very exciting in an advert, does that mean you should always buy it?", a: "No — think about whether you really want or need it" },
                { q: "What might happen if you spend money on lots of small treats?", a: "It can add up to a lot, even if each treat feels small" },
                { q: "What is one way to avoid spending all your money at once?", a: "Decide in advance how much you're happy to spend" },
                { q: "Why is it wise to leave some money unspent?", a: "So you have some left for later or for saving" },
                { q: "What does 'value for money' mean in simple terms?", a: "Getting something good for the price you pay" },
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
                storageKey: 'spendingWiselyGame.settings',
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
                    return 'Think about what a sensible shopper would do before spending their money.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a wise spender!",
            });
        })();
    </script>
@endpush
