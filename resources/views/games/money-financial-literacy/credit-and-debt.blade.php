@extends('layouts.app')

@section('meta_title', 'Credit & Debt — GCSE Financial Literacy Game')
@section('meta_blurb', 'A free GCSE financial literacy game covering credit and debt — credit cards, loans and responsible borrowing concepts.')
@section('meta_words', 'credit and debt game, credit cards explained, loans explained, responsible borrowing, gcse financial literacy game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-credit-card',
        'title' => 'Credit & Debt',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Credit & debt facts'],
        ],
        'aboutTitle' => 'About this credit & debt game',
        'aboutText' => 'This free GCSE financial literacy game covers credit cards, loans, debt and the principles behind responsible borrowing. Choose your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What is 'credit'?", a: "Borrowing money now with an agreement to pay it back later" },
                { q: "What is 'debt'?", a: "Money that is owed and needs to be paid back" },
                { q: "How does a credit card differ from a debit card?", a: "A credit card lets you borrow money to spend, which you repay later, often with interest" },
                { q: "What is a 'loan'?", a: "A sum of money borrowed that is paid back over time, usually with interest" },
                { q: "Why is it important to pay off credit card balances in full each month if possible?", a: "To avoid paying extra interest on what you owe" },
                { q: "What is 'responsible borrowing'?", a: "Only borrowing what you can afford to repay, and understanding the cost" },
                { q: "What could happen if someone repeatedly fails to repay their debts?", a: "It can damage their credit rating and make future borrowing harder or more expensive" },
                { q: "What is a 'credit rating' or 'credit score'?", a: "A measure of how reliable someone is at repaying money they've borrowed" },
                { q: "Why might a bank check someone's credit rating before giving them a loan?", a: "To assess how likely they are to repay it" },
                { q: "What is a mortgage?", a: "A long-term loan used to buy a property, usually repaid over many years" },
                { q: "Why is borrowing sometimes useful, even though it costs interest?", a: "It lets people afford large purchases, like a home, that they couldn't pay for all at once" },
                { q: "What is one danger of relying too heavily on credit cards?", a: "Debts and interest charges can build up faster than you can repay them" },
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
                storageKey: 'creditAndDebtGame.settings',
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
                    return 'Think about the difference between borrowing money and paying it back responsibly.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You understand credit and debt really well!",
            });
        })();
    </script>
@endpush
