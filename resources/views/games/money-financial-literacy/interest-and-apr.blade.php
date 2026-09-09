@extends('layouts.app')

@section('meta_title', 'Interest & APR — GCSE Financial Literacy Game')
@section('meta_blurb', 'A free GCSE financial literacy game covering interest and APR — simple vs compound interest concepts and what APR means.')
@section('meta_words', 'interest and apr game, simple interest, compound interest, apr explained, gcse financial literacy game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-percent',
        'title' => 'Interest & APR',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Interest & APR facts'],
        ],
        'aboutTitle' => 'About this interest & APR game',
        'aboutText' => 'This free GCSE financial literacy game covers the concepts behind simple and compound interest, and what APR means when comparing loans, credit cards and savings. Choose your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What is 'simple interest'?", a: "Interest calculated only on the original amount borrowed or saved" },
                { q: "What is 'compound interest'?", a: "Interest calculated on both the original amount and any interest already added" },
                { q: "Why does compound interest usually grow money faster than simple interest over time?", a: "Because you earn interest on your interest, not just the original amount" },
                { q: "What does APR stand for?", a: "Annual Percentage Rate" },
                { q: "What is APR used for?", a: "To show the yearly cost of borrowing money, including interest and fees" },
                { q: "Why is APR useful when comparing loans or credit cards?", a: "It lets you compare the true cost of different borrowing options fairly" },
                { q: "If a savings account earns compound interest, what happens to the interest each year?", a: "It gets added to the balance, so future interest is earned on a larger amount" },
                { q: "Why might a loan with a lower APR generally be cheaper to repay?", a: "It costs less in interest and fees over the life of the loan" },
                { q: "What is the general relationship between how long you save and how much compound interest adds up?", a: "The longer you save, the more the interest can compound and grow" },
                { q: "Why do lenders use interest to charge for borrowing money?", a: "It's how they earn money for letting someone use their money" },
                { q: "What is the difference between interest you earn and interest you pay?", a: "Interest earned grows your savings; interest paid is the cost of borrowing money" },
                { q: "Why should you always check the interest rate before taking out a loan or opening a savings account?", a: "To understand how much you'll gain from saving or pay for borrowing" },
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
                storageKey: 'interestAndAprGame.settings',
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
                    return 'Think about whether interest is calculated on the original amount only, or on the original amount plus interest already added.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You've mastered interest and APR!",
            });
        })();
    </script>
@endpush
