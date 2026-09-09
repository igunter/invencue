@extends('layouts.app')

@section('meta_title', 'Line Types in Art — Art Game for Kids')
@section('meta_blurb', 'A free art game for young kids exploring the different types of line used in art — thick, thin, wavy, zigzag and dotted.')
@section('meta_words', 'line types in art game, kids art game, thick thin wavy zigzag dotted lines, ks1 art game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-slash-lg',
        'title' => 'Line Types in Art',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Line types'],
        ],
        'aboutTitle' => 'About this line types in art game',
        'aboutText' => 'This free art game helps young kids learn the different types of line artists use, like thick, thin, wavy, zigzag and dotted lines.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Thick line': 'A bold, heavy line that stands out strongly.',
                'Thin line': 'A light, delicate line with little weight.',
                'Wavy line': 'A line that curves smoothly back and forth.',
                'Zigzag line': 'A line made of sharp points going back and forth.',
                'Dotted line': 'A line made up of small dots or dashes.',
                'Straight line': 'A line that goes directly between two points with no curves.',
                'Curved line': 'A line that bends smoothly, with no sharp corners.',
                'Spiral line': 'A line that winds round and round a central point.',
                'Diagonal line': 'A line that slants at an angle, neither flat nor upright.',
                'Horizontal line': 'A flat line that goes straight across, side to side.',
                'Vertical line': 'A line that goes straight up and down.',
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
                storageKey: 'line-types-in-artGame.settings',
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
                        questionText: "What is a '" + term + "' in art?",
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Picture drawing this line on paper — what shape would it make?';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You're a line types superstar!",
            });
        })();
    </script>
@endpush
