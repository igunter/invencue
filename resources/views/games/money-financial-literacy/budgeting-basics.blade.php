@extends('layouts.app')

@section('meta_title', 'Budgeting Basics — Money Game for Kids')
@section('meta_blurb', 'A free money game covering budgeting basics — income vs expenses and simple budgeting concepts.')
@section('meta_words', 'budgeting basics game, income and expenses, kids money game, financial literacy game, ks3 money game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-calculator',
        'title' => 'Budgeting Basics',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Budgeting facts'],
        ],
        'aboutTitle' => 'About this budgeting basics game',
        'aboutText' => 'This free money game covers the basics of budgeting — the difference between income and expenses, and how to plan spending sensibly so you don\'t run out of money. Choose your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What is a budget?", a: "A plan for how you'll spend and save your money" },
                { q: "What is 'income'?", a: "Money that comes in, such as from a job or pocket money" },
                { q: "What are 'expenses'?", a: "Money that goes out, such as things you buy or pay for" },
                { q: "Why is it important that your expenses don't add up to more than your income?", a: "Otherwise you'll run out of money or end up in debt" },
                { q: "What is it called when you have more income than expenses?", a: "A surplus" },
                { q: "What is it called when your expenses are more than your income?", a: "A deficit" },
                { q: "Why might someone write down everything they spend for a week?", a: "To see where their money is really going" },
                { q: "What are 'essential' expenses?", a: "Things you must pay for, like food or transport to school" },
                { q: "What are 'non-essential' expenses?", a: "Things that are nice to have but not necessary, like treats" },
                { q: "What is one benefit of making a budget before you go shopping?", a: "It helps you avoid overspending" },
                { q: "If your income is £20 a month and your planned spending is £25, what's the problem?", a: "You're planning to spend more than you have" },
                { q: "Why should a budget include some money for saving, not just spending?", a: "So you build up savings instead of spending everything" },
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
                storageKey: 'budgetingBasicsGame.settings',
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
                    return 'Think about money coming in compared to money going out.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You've mastered budgeting basics!",
            });
        })();
    </script>
@endpush
