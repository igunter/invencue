@extends('layouts.app')

@section('meta_title', 'Patterns & Symmetry in Art — Art Game for Kids')
@section('meta_blurb', 'A free art game covering patterns and symmetry in art and design — repeating patterns, symmetry and tessellation.')
@section('meta_words', 'patterns and symmetry game, symmetry in art quiz, tessellation, ks3 art game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-grid-3x3',
        'title' => 'Patterns & Symmetry in Art',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Patterns & symmetry'],
        ],
        'aboutTitle' => 'About this patterns & symmetry in art game',
        'aboutText' => 'This free art game covers patterns and symmetry in design, including lines of symmetry, radial symmetry, tessellation and repetition.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Pattern': 'A design made by repeating shapes, lines or colours.',
                'Symmetry': 'When one half of a design is a mirror image of the other half.',
                'Symmetrical': 'A word describing a shape or pattern that looks the same on both sides of a line.',
                'Asymmetrical': 'A word describing a design that is not the same on both sides — unbalanced on purpose.',
                'Line of symmetry': 'An imaginary line that divides a shape into two matching mirror-image halves.',
                'Radial symmetry': 'A pattern that repeats evenly around a central point, like a wheel or flower.',
                'Tessellation': 'A pattern of shapes that fit together perfectly with no gaps or overlaps.',
                'Repetition': 'Repeating a shape, colour or line across a design to create a pattern.',
                'Motif': 'A shape or design that is repeated throughout a pattern.',
                'Reflective symmetry': 'Another name for mirror symmetry, where one half reflects the other.',
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
                storageKey: 'patterns-symmetry-artGame.settings',
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
                        questionText: "What does '" + term + "' mean?",
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about mirror images, repeating shapes, and how designs fit together.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You're a patterns & symmetry superstar!",
            });
        })();
    </script>
@endpush
