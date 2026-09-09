@extends('layouts.app')

@section('meta_title', 'Art Tools & Materials — Art Game for Kids')
@section('meta_blurb', 'A free art game for young kids covering art tools and materials — brushes, paint, crayons, clay and more.')
@section('meta_words', 'art tools game, art materials quiz, kids art game, paintbrush clay crayon, ks1 art game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-brush',
        'title' => 'Art Tools & Materials',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Tools & materials'],
        ],
        'aboutTitle' => 'About this art tools & materials game',
        'aboutText' => 'This free art game helps young kids learn what everyday art tools and materials are used for, from paintbrushes and clay to glue and easels.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Paintbrush': 'Used to apply paint to paper or canvas.',
                'Watercolour paint': 'Paint mixed with water that dries to give a light, see-through colour.',
                'Crayon': 'A stick of coloured wax used for colouring and drawing.',
                'Pencil': 'Used for drawing and sketching lines that can be rubbed out.',
                'Clay': 'A soft, mouldable material used to make models and pottery.',
                'Scissors': 'Used to cut paper and other materials into shapes.',
                'Glue': 'Used to stick paper and materials together in a collage.',
                'Palette': 'A flat surface used for mixing paint colours together.',
                'Easel': 'A stand that holds a canvas or paper upright while an artist works.',
                'Chalk pastel': "A soft, powdery stick of colour that can be blended with a finger.",
                'Canvas': 'A strong fabric, stretched over a frame, that artists paint on.',
                'Sponge': 'Used to dab or blend paint and create textured effects.',
            };
            const TERM_NAMES = Object.keys(TERMS);

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

            function pickOthers(pool, exclude, count) {
                return shuffle(pool.filter(function(x) { return x !== exclude; })).slice(0, count);
            }

            window.ScienceQuiz.run({
                storageKey: 'art-tools-materialsGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const term = TERM_NAMES[randInt(0, TERM_NAMES.length - 1)];
                    return {
                        category: type,
                        label: term,
                        correctText: TERMS[term],
                        questionText: "What is a '" + term + "' used for?",
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about what job this tool or material does when you make art.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You're an art tools superstar!",
            });
        })();
    </script>
@endpush
