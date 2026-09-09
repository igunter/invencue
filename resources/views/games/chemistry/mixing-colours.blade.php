@extends('layouts.app')

@section('meta_title', 'Mixing Colours — Kids Chemistry Game')
@section('meta_blurb', 'A free chemistry game for young kids — predict what colour you get when you mix two colours together, and learn about primary and secondary colours.')
@section('meta_words', 'mixing colours game, kids chemistry game, primary colours, secondary colours, colour mixing for kids')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-palette',
        'title' => 'Mixing Colours',
        'subtitle' => 'Pick your question types, then mix those colours!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'mixing', 'label' => 'What colour do you get?'],
            ['id' => 'facts', 'label' => 'Colour facts'],
        ],
        'aboutTitle' => 'About this mixing colours game',
        'aboutText' => 'This free chemistry game helps young kids predict what colour you get when you mix two colours together, and learn the difference between primary and secondary colours. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const MIXES = {
                'Red + Yellow': 'Orange',
                'Blue + Yellow': 'Green',
                'Red + Blue': 'Purple',
                'Red + White': 'Pink',
                'Black + White': 'Grey',
            };
            const MIX_NAMES = Object.keys(MIXES);
            const COLOUR_LIST = ['Orange', 'Green', 'Purple', 'Pink', 'Grey', 'Brown'];

            const FACTS = {
                'Primary colours': "Red, blue and yellow — colours that can't be made by mixing other colours together.",
                'Secondary colours': 'Orange, green and purple — made by mixing two primary colours together.',
                'Mixing all three primary colours': 'Usually makes a muddy brown colour.',
            };
            const FACT_NAMES = Object.keys(FACTS);

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
                storageKey: 'mixingColoursGame.settings',
                types: ['mixing', 'facts'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'mixing') {
                        const mix = MIX_NAMES[randInt(0, MIX_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: MIXES[mix],
                            questionText: 'What colour do you get when you mix ' + mix.toLowerCase() + '?',
                        };
                    }
                    const fact = FACT_NAMES[randInt(0, FACT_NAMES.length - 1)];
                    return {
                        category: type,
                        label: fact,
                        correctText: FACTS[fact],
                        questionText: "What are '" + fact + "'?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'mixing') {
                        const distractors = pickOthers(COLOUR_LIST, q.correctText, 3);
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(FACT_NAMES, q.label, 3).map(function(f) { return FACTS[f]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'mixing') {
                        return 'Picture the two paint pots being stirred together — what new colour would appear?';
                    }
                    return 'Think about whether this is about colours you start with, colours you make, or what happens with all of them together.';
                },

                explanationFor: function(q) {
                    if (q.category === 'facts') return q.label + ': ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a colour mixing superstar!",
            });
        })();
    </script>
@endpush
