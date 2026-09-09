@extends('layouts.app')

@section('meta_title', 'Industrial Revolution — History Game for Kids')
@section('meta_blurb', 'A free history game covering the Industrial Revolution — steam engines, factories, coal and railways.')
@section('meta_words', 'industrial revolution game, history game for kids, steam engine factories quiz, railways coal, ks2 history game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-gear',
        'title' => 'Industrial Revolution',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Industry facts'],
        ],
        'aboutTitle' => 'About this Industrial Revolution game',
        'aboutText' => 'This free history game covers key facts about the Industrial Revolution, when steam power, factories and railways transformed Britain from the 1760s onwards.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What type of engine, improved by James Watt, powered many factories and trains?", a: "The steam engine" },
                { q: "What do we call the period of rapid growth in factories and machinery in Britain from the 1760s onwards?", a: "The Industrial Revolution" },
                { q: "What raw material, dug from mines, was burned to power steam engines?", a: "Coal" },
                { q: "What were the large buildings full of machines where goods were mass-produced called?", a: "Factories" },
                { q: "What form of transport, running on iron rails, transformed travel during this period?", a: "The railway" },
                { q: "What term describes towns that grew rapidly around new factories during this period?", a: "Industrial towns" },
                { q: "What spinning machine, invented by James Hargreaves, allowed one worker to spin multiple threads of cotton at once?", a: "The spinning jenny" },
                { q: "What term describes children as young as five or six being made to work long hours in factories and mines?", a: "Child labour" },
                { q: "What laws were introduced to limit working hours and improve conditions for workers, especially children?", a: "Factory Acts" },
                { q: "What waterways, built to transport heavy goods like coal, criss-crossed Britain before railways took over?", a: "Canals" },
                { q: "What term describes the rapid growth of towns and cities as people moved from the countryside to work in factories?", a: "Urbanisation" },
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
                storageKey: 'industrial-revolutionGame.settings',
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
                    return 'Think carefully about the topic and time period.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're an Industrial Revolution superstar!",
            });
        })();
    </script>
@endpush
