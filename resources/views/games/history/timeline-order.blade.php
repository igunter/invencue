@extends('layouts.app')

@section('meta_title', 'Timeline Order — History Game for Kids')
@section('meta_blurb', 'A free history game for kids — put famous periods of British history in the right order.')
@section('meta_words', 'timeline game, history game for kids, which came first, stone age romans tudors victorians, primary school history')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-clock-history',
        'title' => 'Timeline Order',
        'subtitle' => 'Pick two time periods, then say which came first!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Which came first?'],
        ],
        'aboutTitle' => 'About this timeline game',
        'aboutText' => 'This free history game helps kids build a sense of chronology — which famous periods of British history, from the Stone Age to modern times, happened before others. Answer as many as you can to build a timeline in your head.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const ITEMS = [
                { name: "Stone Age", order: 1 },
                { name: "Bronze Age", order: 2 },
                { name: "Iron Age", order: 3 },
                { name: "Roman Britain", order: 4 },
                { name: "Anglo-Saxons", order: 5 },
                { name: "Vikings", order: 6 },
                { name: "Medieval Times", order: 7 },
                { name: "Tudors", order: 8 },
                { name: "Victorians", order: 9 },
                { name: "Modern Day", order: 10 },
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
                storageKey: 'timeline-orderGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-6',

                buildQuestion: function(type) {
                    let a, b;
                    do {
                        a = ITEMS[randInt(0, ITEMS.length - 1)];
                        b = ITEMS[randInt(0, ITEMS.length - 1)];
                    } while (a.name === b.name);
                    const correct = a.order < b.order ? a.name : b.name;
                    return {
                        category: type,
                        a: a.name,
                        b: b.name,
                        correctText: correct,
                        questionText: 'Which came first: ' + a.name + ' or ' + b.name + '?',
                    };
                },

                buildChoices: function(q) {
                    return shuffle([q.a, q.b]);
                },

                hintFor: function() {
                    return 'Think about which one happened further back in time.';
                },

                explanationFor: function(q) {
                    return q.correctText + ' came first.';
                },

                masteryMessage: "Amazing! You're a history timeline superstar!",
            });
        })();
    </script>
@endpush
