@extends('layouts.app')

@section('meta_title', 'Light & Shadows — Kids Physics Game')
@section('meta_blurb', 'A free physics game for young kids — predict how shadows change, and learn about light sources, transparent, translucent and opaque materials.')
@section('meta_words', 'light and shadows game, kids physics game, transparent translucent opaque, light source, shadow size')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-lightbulb',
        'title' => 'Light & Shadows',
        'subtitle' => 'Pick your question types, then test your light knowledge!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'facts', 'label' => 'Light & shadow facts'],
            ['id' => 'predict', 'label' => 'What happens?'],
        ],
        'aboutTitle' => 'About this light & shadows game',
        'aboutText' => 'This free physics game helps young kids learn what light sources, shadows, transparent, translucent and opaque materials are, and predict how a shadow changes as a light source moves. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = {
                'Shadow': 'A dark shape made when an object blocks light from reaching a surface.',
                'Light source': 'Something that makes its own light, like the Sun or a light bulb.',
                'Transparent': 'A material that lets almost all light pass through, like clear glass.',
                'Opaque': 'A material that blocks light completely, so no light passes through.',
                'Translucent': "A material that lets some light pass through, but you can't see clearly through it.",
                'Reflection': 'When light bounces off a surface, like a mirror.',
                'Luminous object': 'An object that gives out its own light, like the Sun or a lamp.',
                'Non-luminous object': 'An object that does not make its own light, but can reflect light from elsewhere, like the Moon.',
                'Umbra': 'The darkest, central part of a shadow, where light is completely blocked.',
                'Reflective material': 'A material like a mirror or shiny metal that reflects most of the light that hits it.',
            };
            const FACT_NAMES = Object.keys(FACTS);

            const PREDICT = {
                'Moving a torch closer to an object': 'The shadow gets bigger.',
                'Moving a torch further away from an object': 'The shadow gets smaller.',
                'Shining a light on a see-through object like glass': 'Little to no shadow is made, because light passes through.',
                'Shining a light on a solid object like a book': 'A dark, clear shadow is made.',
                'Moving the object itself closer to the torch': 'The shadow gets bigger still, because the object blocks more of the spreading light.',
                'Moving the object itself closer to the screen': 'The shadow gets smaller and sharper.',
                'Using two torches shining from different angles': 'Two overlapping shadows are made, one from each light source.',
                'Shining a light directly above an object, straight down': 'A short shadow is made, right underneath the object.',
                'Shining a light at a low angle, near the ground': 'A long, stretched-out shadow is made.',
                'Shining a light on a translucent material like tracing paper': 'A faint, blurry shadow is made, because some light passes through.',
            };
            const PREDICT_NAMES = Object.keys(PREDICT);

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
                storageKey: 'lightShadowsGame.settings',
                types: ['facts', 'predict'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'facts') {
                        const term = FACT_NAMES[randInt(0, FACT_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: FACTS[term],
                            questionText: "What is a '" + term + "'?",
                        };
                    }
                    const scenario = PREDICT_NAMES[randInt(0, PREDICT_NAMES.length - 1)];
                    return {
                        category: type,
                        label: scenario,
                        correctText: PREDICT[scenario],
                        questionText: scenario + ' — what happens?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'facts') {
                        const term = FACT_NAMES.find(function(t) { return FACTS[t] === q.correctText; });
                        const distractors = pickOthers(FACT_NAMES, term, 3).map(function(t) { return FACTS[t]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(PREDICT_NAMES, q.label, 3).map(function(p) { return PREDICT[p]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'facts') {
                        return 'Think about whether it makes light, blocks it completely, blocks it a little, or lets it all through.';
                    }
                    return 'Think about the torch getting closer or further away, and whether light can pass through the object.';
                },

                explanationFor: function(q) {
                    if (q.category === 'predict') return q.label + ': ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a light and shadows superstar!",
            });
        })();
    </script>
@endpush
