@extends('layouts.app')

@section('meta_title', 'Where Money Comes From — Money Game for Kids')
@section('meta_blurb', 'A free money game for young kids about how people get money — earning, jobs, pocket money and gifts.')
@section('meta_words', 'where money comes from game, kids money game, earning money for kids, pocket money game, ks1 money game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-briefcase',
        'title' => 'Where Money Comes From',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Money facts'],
        ],
        'aboutTitle' => 'About this where money comes from game',
        'aboutText' => 'This free money game helps young kids understand where money comes from — jobs and earning, pocket money, and gifts. Choose your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What do most grown-ups do to earn money?", a: "Go to work / do a job" },
                { q: "What is money given to children for doing chores or as an allowance called?", a: "Pocket money" },
                { q: "What do we call money someone gives you as a present, like on your birthday?", a: "A gift" },
                { q: "What is it called when someone pays you for doing work?", a: "Earning money / getting paid" },
                { q: "If you sell something you made, like lemonade, what are you doing?", a: "Earning money by selling" },
                { q: "What do we call the money a worker is paid for doing their job?", a: "Wages / a salary" },
                { q: "Where might pocket money come from?", a: "Parents or carers, often for doing jobs around the house" },
                { q: "What is it called when you do a small job for a neighbour, like walking their dog, for money?", a: "Doing a job / earning money" },
                { q: "Besides working, name another way someone might receive money.", a: "Being given it as a gift" },
                { q: "Why do people go to work?", a: "To earn money to pay for things they need and want" },
                { q: "What could you do with money you earn from a small job or chore?", a: "Save it, spend it, or share some of it" },
                { q: "Is money picked up off the street the same as earned money?", a: "No — earned money comes from doing work, not from finding it" },
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
                storageKey: 'whereMoneyComesFromGame.settings',
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
                    return 'Think about the different ways people get money — by working, or by being given it.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You know exactly where money comes from!",
            });
        })();
    </script>
@endpush
