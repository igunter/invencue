@extends('layouts.app')

@section('meta_title', 'Map Skills — Geography Game for Kids')
@section('meta_blurb', 'A free geography game — learn map scale, grid references, contour lines and other key map skills.')
@section('meta_words', 'map skills game, geography game for kids, grid references, contour lines, ordnance survey maps, ks2 geography')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-map',
        'title' => 'Map Skills',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Map skills'],
        ],
        'aboutTitle' => 'About this map skills game',
        'aboutText' => 'This free geography game builds on the basics of map reading, covering scale, grid references, contour lines and other key skills needed to read Ordnance Survey-style maps.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What is the term for the relationship between a distance on a map and the real distance on the ground?", a: "Scale" },
                { q: "What do we call the numbers used to describe a location on a map using its eastings and northings?", a: "A grid reference" },
                { q: "On a grid reference, which number is normally given first: the eastings or the northings?", a: "The eastings" },
                { q: "What are the lines on a map called that join up points of equal height?", a: "Contour lines" },
                { q: "If contour lines on a map are very close together, what does this tell you about the land?", a: "It is very steep" },
                { q: "What is the name for the box on a map that explains what each symbol means?", a: "The key (or legend)" },
                { q: "What is the general term for the shape and height of the land, including hills and valleys?", a: "Relief" },
                { q: "A four-figure grid reference identifies which of these?", a: "A whole grid square" },
                { q: "A six-figure grid reference identifies which of these?", a: "A precise point within a grid square" },
                { q: "What compass direction is normally at the top of an Ordnance Survey map?", a: "North" },
                { q: "What do we call the vertical grid lines on a map, used for the first half of a grid reference?", a: "Eastings" },
                { q: "What do we call the horizontal grid lines on a map, used for the second half of a grid reference?", a: "Northings" },
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
                storageKey: 'mapSkillsGame.settings',
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
                    return 'Think about how mapmakers show distance, position and height on a flat map.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a map skills superstar!",
            });
        })();
    </script>
@endpush
