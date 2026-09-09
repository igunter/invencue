@extends('layouts.app')

@section('meta_title', 'Shapes in Art — Art Game for Kids')
@section('meta_blurb', 'A free art game for young kids covering the shapes used in pictures — circles, squares, triangles and more.')
@section('meta_words', 'shapes in art game, kids art game, geometric shapes quiz, ks1 art game, 2d shapes')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-shapes',
        'title' => 'Shapes in Art',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Shape facts'],
        ],
        'aboutTitle' => 'About this shapes in art game',
        'aboutText' => 'This free art game helps young kids recognise and name the shapes artists use in pictures, from circles and squares to pentagons and hexagons.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What shape has three straight sides?", a: "Triangle" },
                { q: "What shape has four equal sides and four right angles?", a: "Square" },
                { q: "What shape is perfectly round with no corners?", a: "Circle" },
                { q: "What shape has four sides but is not a square, like a door?", a: "Rectangle" },
                { q: "What do we call shapes like circles and ovals that have curved edges?", a: "Organic (curved) shapes" },
                { q: "What do we call shapes like squares and triangles that have straight edges and points?", a: "Geometric shapes" },
                { q: "How many sides does a pentagon have?", a: "Five" },
                { q: "How many sides does a hexagon have?", a: "Six" },
                { q: "What shape is a stop sign?", a: "Octagon (eight sides)" },
                { q: "What do artists call a flat, closed shape with only length and width?", a: "A 2D shape" },
                { q: "What shape is a wheel or a clock face?", a: "Circle" },
                { q: "What shape has four sides that can all be different lengths, like a kite?", a: "Quadrilateral" },
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
                storageKey: 'shapes-in-artGame.settings',
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
                    return 'Count the sides and corners, or think about whether the edges are straight or curved.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a shapes in art superstar!",
            });
        })();
    </script>
@endpush
