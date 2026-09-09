@extends('layouts.app')

@section('meta_title', 'Warm & Cool Colours — Art Game for Kids')
@section('meta_blurb', 'A free art game for young kids sorting colours into warm colours and cool colours.')
@section('meta_words', 'warm and cool colours game, kids art game, colour sorting quiz, ks1 art game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-thermometer-half',
        'title' => 'Warm & Cool Colours',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Warm or cool?'],
        ],
        'aboutTitle' => 'About this warm & cool colours game',
        'aboutText' => 'This free art game helps young kids sort colours into warm colours, like red and orange, and cool colours, like blue and green.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const COLOURS = {
                'Red': 'Warm',
                'Orange': 'Warm',
                'Yellow': 'Warm',
                'Blue': 'Cool',
                'Green': 'Cool',
                'Purple': 'Cool',
                'Pink': 'Warm',
                'Turquoise': 'Cool',
                'Maroon': 'Warm',
                'Teal': 'Cool',
                'Gold': 'Warm',
                'Lavender': 'Cool',
            };
            const COLOUR_NAMES = Object.keys(COLOURS);

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
                storageKey: 'warm-cool-coloursGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const colour = COLOUR_NAMES[randInt(0, COLOUR_NAMES.length - 1)];
                    return {
                        category: type,
                        correctText: COLOURS[colour],
                        questionText: colour + ' — is this a warm colour or a cool colour?',
                    };
                },

                buildChoices: function(q) {
                    return shuffle(['Warm', 'Cool']);
                },

                hintFor: function() {
                    return 'Warm colours can feel like fire and sunshine. Cool colours can feel like water and ice.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a warm & cool colours superstar!",
            });
        })();
    </script>
@endpush
