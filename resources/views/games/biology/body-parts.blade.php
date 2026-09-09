@extends('layouts.app')

@section('meta_title', 'Body Parts — Kids Biology Game')
@section('meta_blurb', 'A free biology game for young kids — match human body parts to what they do, and how many of each we have.')
@section('meta_words', 'body parts game, kids biology game, human body, heart lungs brain, learn body parts')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-person-badge',
        'title' => 'Body Parts',
        'subtitle' => 'Pick your question types, then test your body knowledge!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'function', 'label' => 'What does it do?'],
            ['id' => 'howMany', 'label' => 'How many do we have?'],
        ],
        'aboutTitle' => 'About this body parts game',
        'aboutText' => 'This free biology game helps young kids learn what different body parts do — the heart, lungs, brain, stomach and more — and how many of each we have. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FUNCTIONS = {
                'Heart': 'Pumps blood around your body.',
                'Lungs': 'Help you breathe in air.',
                'Brain': 'Controls your body and helps you think.',
                'Stomach': 'Helps digest the food you eat.',
                'Eyes': 'Let you see the world around you.',
                'Ears': 'Let you hear sounds.',
                'Skin': 'Covers and protects your body.',
                'Muscles': 'Help your body move.',
                'Bones': 'Give your body its shape and support.',
            };
            const PART_NAMES = Object.keys(FUNCTIONS);

            const COUNTS = {
                'eyes': 2,
                'ears': 2,
                'legs': 2,
                'arms': 2,
                'fingers on one hand': 5,
                'heart': 1,
                'nose': 1,
            };
            const COUNT_PARTS = Object.keys(COUNTS);
            const NUMBER_POOL = [1, 2, 3, 4, 5, 10];

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
                storageKey: 'bodyPartsGame.settings',
                types: ['function', 'howMany'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'function') {
                        const part = PART_NAMES[randInt(0, PART_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: FUNCTIONS[part],
                            questionText: 'What does your ' + part.toLowerCase() + ' do?',
                        };
                    }
                    const part = COUNT_PARTS[randInt(0, COUNT_PARTS.length - 1)];
                    return {
                        category: type,
                        correctText: String(COUNTS[part]),
                        questionText: 'How many ' + part + ' does a person have?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'function') {
                        const part = PART_NAMES.find(function(p) { return FUNCTIONS[p] === q.correctText; });
                        const distractors = pickOthers(PART_NAMES, part, 3).map(function(p) { return FUNCTIONS[p]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(NUMBER_POOL.map(String), q.correctText, 3);
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'function') {
                        return 'Think about whether it helps you move, think, breathe, see, hear, or protects the outside of you.';
                    }
                    return 'Try counting on your own body!';
                },

                explanationFor: function(q) {
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a body parts superstar!",
            });
        })();
    </script>
@endpush
