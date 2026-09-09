@extends('layouts.app')

@section('meta_title', 'Value for Money — Money Game for Kids')
@section('meta_blurb', 'A free money game covering value for money — comparing prices, discounts, sales and working out the best deal.')
@section('meta_words', 'value for money game, comparing prices, discounts game, kids money game, ks3 money game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-tags',
        'title' => 'Value for Money',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Value for money facts'],
        ],
        'aboutTitle' => 'About this value for money game',
        'aboutText' => 'This free money game covers how to spot good value for money — comparing prices, understanding discounts and sales, and working out the best deal. Choose your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What does 'value for money' mean?", a: "Getting the best quality or amount for the price you pay" },
                { q: "Why might a bigger pack of something sometimes be better value?", a: "It can work out cheaper per item than buying smaller packs" },
                { q: "What is a 'discount'?", a: "A reduction in the usual price of something" },
                { q: "What should you compare to work out which of two products is the best deal?", a: "The price compared to the quality or amount you get" },
                { q: "What is a 'sale'?", a: "A period when shops sell items at reduced prices" },
                { q: "Why might the cheapest item not always be the best value?", a: "It might be lower quality or not last as long" },
                { q: "What is 'price per item' or 'unit price' useful for?", a: "Comparing the cost of similar products fairly" },
                { q: "Why should you compare prices in different shops before buying something expensive?", a: "To make sure you're getting a fair price and not overpaying" },
                { q: "What is a loyalty card or reward scheme used for?", a: "Giving customers discounts or points for shopping regularly at a store" },
                { q: "Why might buying own-brand instead of a well-known brand save money?", a: "Own-brand products are often cheaper but can be similar quality" },
                { q: "What's one downside of always buying the cheapest option?", a: "It might break quickly or not do the job as well" },
                { q: "Why is it worth reading reviews before buying something?", a: "To check it's good quality and worth the price" },
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
                storageKey: 'valueForMoneyGame.settings',
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
                    return 'Think about comparing price against quality or amount, not just picking the cheapest.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You really know how to spot a good deal!",
            });
        })();
    </script>
@endpush
