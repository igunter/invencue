@extends('layouts.app')

@section('meta_title', 'Floating & Sinking — Kids Chemistry Game')
@section('meta_blurb', 'A free chemistry game for young kids — predict whether an object will float or sink, and learn why.')
@section('meta_words', 'floating and sinking game, kids chemistry game, density for kids, does it float or sink')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-circle',
        'title' => 'Floating & Sinking',
        'subtitle' => 'Pick your question types, then make your prediction!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'predict', 'label' => 'Float or sink?'],
            ['id' => 'why', 'label' => 'Why does it happen?'],
        ],
        'aboutTitle' => 'About this floating & sinking game',
        'aboutText' => 'This free chemistry game helps young kids predict whether everyday objects will float or sink in water, and starts to explain why — because of how heavy something is for its size. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const OBJECTS = {
                'Wooden block': 'Float',
                'Leaf': 'Float',
                'Rubber duck': 'Float',
                'An empty, sealed plastic bottle': 'Float',
                'Rock': 'Sink',
                'Coin': 'Sink',
                'Metal spoon': 'Sink',
                'Marble': 'Sink',
                'Beach ball': 'Float',
                'Cork': 'Float',
                'Brick': 'Sink',
                'Golf ball': 'Sink',
            };
            const OBJECT_NAMES = Object.keys(OBJECTS);

            const WHY = {
                'Why do heavy, dense objects like rocks sink?': 'Because their material is packed tightly and weighs a lot for its size, so gravity pulls it down through the water.',
                'Why do light objects like leaves float?': 'Because their material is light for its size, so the water can push up and support its weight.',
                "Why does a big empty bottle float, even though it's plastic?": "Because it's full of air, which is very light, making the whole bottle light for its size.",
                'Why does a small coin sink, even though it is small?': 'Because metal is very dense — heavy for its size — so it sinks even in small pieces.',
                'Why does a golf ball sink but a beach ball float?': 'The golf ball is packed solid and dense, while the beach ball is full of light air, so it stays light for its size.',
                'Why does a cork float?': 'Cork is very light for its size, so the water easily pushes up and supports its weight.',
                'Why does a heavy brick sink?': 'A brick is packed tightly and is heavy for its size, so gravity pulls it down through the water.',
                'What is the water pushing up on an object called?': 'Upthrust — an upward push from the water that can support an object if it is light enough for its size.',
                'Why do ships made of heavy metal float?': "A ship's hollow shape spreads its weight over a large area and traps air inside, making it light enough for its size overall.",
                'What happens if an object is denser than water?': "It sinks, because it is heavier than the water it pushes out of the way.",
            };
            const WHY_NAMES = Object.keys(WHY);

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
                storageKey: 'floatingSinkingGame.settings',
                types: ['predict', 'why'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'predict') {
                        const object = OBJECT_NAMES[randInt(0, OBJECT_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: OBJECTS[object],
                            questionText: 'Will a ' + object.toLowerCase() + ' float or sink in water?',
                        };
                    }
                    const question = WHY_NAMES[randInt(0, WHY_NAMES.length - 1)];
                    return {
                        category: type,
                        label: question,
                        correctText: WHY[question],
                        questionText: question,
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'predict') {
                        return shuffle(['Float', 'Sink']);
                    }
                    const distractors = pickOthers(WHY_NAMES, q.label, 3).map(function(w) { return WHY[w]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'predict') {
                        return 'Think about how heavy it feels compared to its size — not just whether it looks heavy!';
                    }
                    return 'Think about how heavy the object is compared to its size, and what it is filled with.';
                },

                explanationFor: function(q) {
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a floating and sinking superstar!",
            });
        })();
    </script>
@endpush
