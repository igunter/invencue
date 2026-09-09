@extends('layouts.app')

@section('meta_title', 'Saving Money — Money Game for Kids')
@section('meta_blurb', 'A free money game for young kids covering why and how we save — piggy banks, savings goals and good money habits.')
@section('meta_words', 'saving money game, kids money game, piggy bank game, financial literacy for kids, ks1 money game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-piggy-bank',
        'title' => 'Saving Money',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Saving facts'],
        ],
        'aboutTitle' => 'About this saving money game',
        'aboutText' => 'This free money game covers the basics of saving for young kids — what a piggy bank is for, why we save instead of spending everything, and how saving a little bit at a time helps you reach a goal. Choose your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What do we call a container used to save coins at home?", a: "A piggy bank" },
                { q: "What is it called when you keep money instead of spending it?", a: "Saving" },
                { q: "Why might someone save up money over several weeks?", a: "To buy something they want that costs more than they have" },
                { q: "What is a good habit when you get pocket money or a gift of money?", a: "Save some of it instead of spending all of it" },
                { q: "What do we call a place where people can keep their money safe?", a: "A bank" },
                { q: "What is it called when you set money aside for something specific, like a bike?", a: "Saving for a goal" },
                { q: "If you save a little bit each week, what happens to your savings over time?", a: "It adds up and grows bigger" },
                { q: "What might happen if you spend all your money as soon as you get it?", a: "You won't have any left for things you want later" },
                { q: "What's one way to save money faster?", a: "Save a bit more each week or spend less on other things" },
                { q: "What do we call the amount of money you have saved up?", a: "Your savings" },
                { q: "Why is it a good idea to make a savings goal, like saving £10 for a toy?", a: "It helps you know how much to save and when you'll reach it" },
                { q: "What could you use a savings jar or piggy bank for?", a: "To keep coins and notes safe until you're ready to spend or bank them" },
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
                storageKey: 'savingMoneyGame.settings',
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
                    return 'Think about why we keep some money instead of spending it straight away.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a super saver!",
            });
        })();
    </script>
@endpush
