@extends('layouts.app')

@section('meta_title', 'Compass Directions — Geography Game for Kids')
@section('meta_blurb', 'A free geography game for young kids — learn north, south, east, west and the directions in between.')
@section('meta_words', 'compass directions game, geography game for kids, north south east west, compass points, ks1 ks2 geography')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-compass',
        'title' => 'Compass Directions',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Compass facts'],
        ],
        'aboutTitle' => 'About this compass directions game',
        'aboutText' => 'This free geography game helps young kids learn the four main compass points — north, south, east and west — plus the four in-between points, and how a compass helps us find our way.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What is the direction directly opposite North on a compass?", a: "South" },
                { q: "What is the direction directly opposite East on a compass?", a: "West" },
                { q: "What direction is halfway between North and East?", a: "North-East" },
                { q: "What direction is halfway between South and West?", a: "South-West" },
                { q: "What direction is halfway between North and West?", a: "North-West" },
                { q: "What direction is halfway between South and East?", a: "South-East" },
                { q: "On most maps, which direction points to the top of the page?", a: "North" },
                { q: "How many main compass points are there — North, South, East and West?", a: "Four" },
                { q: "What tool has a needle that always points North, helping you find your way?", a: "A compass" },
                { q: "If you are facing North and turn to face the opposite way, which direction are you now facing?", a: "South" },
                { q: "If you are facing East and turn to face the opposite way, which direction are you now facing?", a: "West" },
                { q: "Including the four in-between points, how many compass points are on a compass rose in total?", a: "Eight" },
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
                storageKey: 'compassDirectionsGame.settings',
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
                    return 'Picture a compass rose: North at the top, East to the right, South at the bottom, West to the left.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a compass directions superstar!",
            });
        })();
    </script>
@endpush
