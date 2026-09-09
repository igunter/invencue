@extends('layouts.app')

@section('meta_title', 'British Monarchs — History Game for Kids')
@section('meta_blurb', 'A free history game — put famous British monarchs in the order they ruled.')
@section('meta_words', 'british monarchs game, history game for kids, kings and queens timeline, which came first, ks2 history game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-award',
        'title' => 'British Monarchs',
        'subtitle' => 'Pick two monarchs, then say which ruled first!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Which ruled first?'],
        ],
        'aboutTitle' => 'About this British monarchs game',
        'aboutText' => 'This free history game helps kids build a sense of chronology across British history by comparing when famous monarchs ruled, from William the Conqueror to the present day.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const ITEMS = [
                { name: "William the Conqueror", order: 1 },
                { name: "King John", order: 2 },
                { name: "Henry VIII", order: 3 },
                { name: "Elizabeth I", order: 4 },
                { name: "Charles I", order: 5 },
                { name: "Queen Victoria", order: 6 },
                { name: "Elizabeth II", order: 7 },
                { name: "Charles III", order: 8 },
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
                storageKey: 'british-monarchsGame.settings',
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

                masteryMessage: "Amazing! You're a monarchs timeline superstar!",
            });
        })();
    </script>
@endpush
