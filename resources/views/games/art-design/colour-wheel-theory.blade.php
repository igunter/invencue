@extends('layouts.app')

@section('meta_title', 'Colour Wheel & Colour Theory — Art Game for Kids')
@section('meta_blurb', 'A free art game covering colour theory — complementary, tertiary and analogous colours on the colour wheel.')
@section('meta_words', 'colour wheel game, colour theory quiz, complementary colours, analogous colours, ks3 art game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-circle-half',
        'title' => 'Colour Wheel & Colour Theory',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Colour theory'],
        ],
        'aboutTitle' => 'About this colour wheel & colour theory game',
        'aboutText' => 'This free art game covers colour theory terms used with the colour wheel, including complementary, tertiary, analogous and monochromatic colours, plus tints and shades.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Complementary colours': 'Colours that sit opposite each other on the colour wheel, like red and green.',
                'Primary colours': 'Red, blue and yellow — the colours all others are mixed from.',
                'Secondary colours': 'Orange, green and purple — made by mixing two primary colours.',
                'Tertiary colours': 'Colours made by mixing a primary colour with a neighbouring secondary colour, like red-orange.',
                'Analogous colours': 'Colours that sit next to each other on the colour wheel, like blue, blue-green and green.',
                'Colour wheel': 'A circular diagram showing how colours relate to each other.',
                'Monochromatic': 'Using different shades and tints of just one colour.',
                'Hue': 'The name of a colour, such as red or blue.',
                'Tint': 'A colour made lighter by adding white.',
                'Shade': 'A colour made darker by adding black.',
                'Warm colours': 'Colours like red, orange and yellow that can feel energetic or hot.',
                'Cool colours': 'Colours like blue, green and purple that can feel calm or cold.',
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
                storageKey: 'colour-wheel-theoryGame.settings',
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
                    return 'Think about where colours sit on the colour wheel, or what white and black do to a colour.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You're a colour theory superstar!",
            });
        })();
    </script>
@endpush
