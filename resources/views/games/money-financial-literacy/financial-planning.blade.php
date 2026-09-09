@extends('layouts.app')

@section('meta_title', 'Financial Planning — GCSE Financial Literacy Game')
@section('meta_blurb', 'A free GCSE financial literacy game covering financial planning — budgeting, saving for the future and what a pension is.')
@section('meta_words', 'financial planning game, pension explained, saving for the future, gcse financial literacy game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-clipboard-data',
        'title' => 'Financial Planning',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Financial planning facts'],
        ],
        'aboutTitle' => 'About this financial planning game',
        'aboutText' => 'This free GCSE financial literacy game covers the basics of financial planning — budgeting, saving for the future, emergency funds, and what a pension is. Choose your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What is 'financial planning'?", a: "Thinking ahead and making decisions about how to manage your money over time" },
                { q: "What is a 'pension'?", a: "Money saved over your working life to live on after you retire" },
                { q: "Why is it a good idea to start saving into a pension early?", a: "The longer money is saved and invested, the more it can grow over time" },
                { q: "What is an 'emergency fund'?", a: "Money set aside to cover unexpected costs, like a broken boiler or losing a job" },
                { q: "Why is budgeting an important part of financial planning?", a: "It helps you balance spending, saving, and planning for the future" },
                { q: "What is 'retirement'?", a: "The stage of life when someone stops working, often relying on pensions and savings" },
                { q: "What is a long-term financial plan likely to include?", a: "Goals like saving for a home, a pension, and an emergency fund" },
                { q: "Why might someone review their financial plan regularly?", a: "Their income, costs and goals can change over time" },
                { q: "What is one benefit of planning your finances instead of dealing with money as it comes?", a: "It helps you avoid debt and reach bigger goals more reliably" },
                { q: "Why do many workplaces automatically enrol employees into a pension scheme?", a: "To help people build up savings for retirement without having to remember to do it" },
                { q: "What could happen if someone doesn't plan or save at all for the future?", a: "They may struggle financially later in life, especially after retirement" },
                { q: "What is the difference between short-term budgeting and long-term financial planning?", a: "Budgeting manages day-to-day money; financial planning looks at bigger goals over years" },
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
                storageKey: 'financialPlanningGame.settings',
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
                    return 'Think about how planning ahead — for both short-term and long-term needs — helps manage money well.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You've mastered financial planning!",
            });
        })();
    </script>
@endpush
