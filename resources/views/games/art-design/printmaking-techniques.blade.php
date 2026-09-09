@extends('layouts.app')

@section('meta_title', 'Printmaking & Techniques — Art Game for Kids')
@section('meta_blurb', 'A free art game covering simple printmaking techniques — lino print, stamping, relief printing and more.')
@section('meta_words', 'printmaking game, lino print quiz, stamping art, screen printing, ks3 art game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-stamp',
        'title' => 'Printmaking & Techniques',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Printmaking terms'],
        ],
        'aboutTitle' => 'About this printmaking & techniques game',
        'aboutText' => 'This free art game covers simple printmaking methods, from lino printing and stamping to monoprints and screen printing.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Lino print': 'A print made by carving a design into a sheet of linoleum, inking it, then pressing it onto paper.',
                'Stamping': 'Pressing an inked shape, like a sponge or carved block, onto paper to repeat a design.',
                'Relief printing': 'A printmaking method where the raised parts of a carved surface are inked and printed.',
                'Monoprint': 'A one-off print made by drawing or painting onto a surface and pressing it onto paper just once.',
                'Printing block': 'A carved surface, made from lino, wood or foam, used to stamp a repeated design.',
                'Mirror image': 'What a print shows, because the carved design is flipped when it is pressed onto paper.',
                'Brayer': 'A small roller used to spread ink evenly over a printing block.',
                'Registration': 'Lining up a print correctly so multiple colours or layers match up.',
                'Potato printing': 'A simple printmaking technique using a shape carved into a cut potato.',
                'Screen printing': 'A technique that pushes ink through a fine mesh screen to print a design.',
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
                storageKey: 'printmaking-techniquesGame.settings',
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
                        questionText: "What is '" + term + "'?",
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about how the design is made, and how it gets transferred onto paper.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You're a printmaking superstar!",
            });
        })();
    </script>
@endpush
