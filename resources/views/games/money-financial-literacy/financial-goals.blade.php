@extends('layouts.app')

@section('meta_title', 'Financial Goals — Money Game for Kids')
@section('meta_blurb', 'A free money game covering financial goals — short-term vs long-term saving goals and how to reach them.')
@section('meta_words', 'financial goals game, saving goals for kids, short term long term saving, kids money game, ks3 money game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-flag',
        'title' => 'Financial Goals',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Goal-setting facts'],
        ],
        'aboutTitle' => 'About this financial goals game',
        'aboutText' => 'This free money game covers short-term and long-term financial goals, and how setting a clear savings target helps you get there. Choose your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What is a short-term financial goal?", a: "Something you save for that you want fairly soon, like a game" },
                { q: "What is a long-term financial goal?", a: "Something you save for over a longer time, like a car or a house deposit" },
                { q: "Why is it helpful to set a target amount when saving for something?", a: "You know exactly how much you need to reach your goal" },
                { q: "What is one way to reach a savings goal faster?", a: "Save a bit more regularly, or spend less on other things" },
                { q: "Which of these is an example of a short-term goal: saving for trainers, or saving for a car?", a: "Saving for trainers" },
                { q: "Which of these is an example of a long-term goal: saving for a car, or saving for a comic?", a: "Saving for a car" },
                { q: "Why might someone break a big goal into smaller saving targets?", a: "It makes a big goal feel more manageable" },
                { q: "What could you use to track your progress towards a savings goal?", a: "A savings chart, app, or simply checking your balance" },
                { q: "Why is it useful to write down your financial goals?", a: "It helps you stay focused and motivated to save" },
                { q: "What might happen if you don't set any savings goals at all?", a: "You might spend money without a clear plan or purpose" },
                { q: "Which usually takes longer to save for: a long-term goal or a short-term goal?", a: "A long-term goal" },
                { q: "Why might someone have several financial goals at once?", a: "They may be saving for different things, like a treat now and something bigger later" },
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
                storageKey: 'financialGoalsGame.settings',
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
                    return 'Think about how long something takes to save up for, and why a clear target helps.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a goal-setting superstar!",
            });
        })();
    </script>
@endpush
