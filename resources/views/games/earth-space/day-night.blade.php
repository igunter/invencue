@extends('layouts.app')

@section('meta_title', 'Day & Night — Kids Earth Science Game')
@section('meta_blurb', 'A free earth science game for young kids — learn why we have day and night, and sort activities into day-time or night-time.')
@section('meta_words', 'day and night game, kids earth science game, earth spinning, sunrise sunset, learn about day and night')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-arrow-repeat',
        'title' => 'Day & Night',
        'subtitle' => 'Pick your question types, then test your day and night knowledge!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'facts', 'label' => 'Day & night facts'],
            ['id' => 'time', 'label' => 'Day or night?'],
        ],
        'aboutTitle' => 'About this day & night game',
        'aboutText' => 'This free earth science game helps young kids learn why we get day and night as the Earth spins, and sort everyday activities into day-time or night-time. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = {
                'Day and night': 'Happen because the Earth spins on its axis once every 24 hours.',
                'Day': 'The part of the 24 hours when your part of the Earth faces the Sun.',
                'Night': 'The part of the 24 hours when your part of the Earth faces away from the Sun.',
                'Sunrise': 'When the Sun appears to come up as your part of the Earth turns to face it.',
                'Sunset': 'When the Sun appears to go down as your part of the Earth turns away from it.',
                'Axis': "An imaginary line through the centre of the Earth, from the North Pole to the South Pole, that the Earth spins around.",
                'Rotation': 'The spinning motion of the Earth on its axis, which takes 24 hours and causes day and night.',
                'Midday (noon)': 'The middle of the day, when the Sun is at its highest point in the sky.',
                'Midnight': 'The middle of the night, 12 hours away from midday.',
                'Time zones': "Different regions of the world set their clocks differently, depending on how far the Earth has turned relative to the Sun.",
            };
            const FACT_NAMES = Object.keys(FACTS);

            const TIME_OF_DAY = {
                'Eating breakfast': 'Day',
                'Going to school': 'Day',
                'The Sun shining brightly': 'Day',
                'Sleeping in bed': 'Night',
                'Stars appearing in the sky': 'Night',
                'The Moon being high in the sky': 'Night',
                'Eating lunch': 'Day',
                'Playing outside in the sunshine': 'Day',
                'Brushing your teeth before bed': 'Night',
                'The street lights turning on': 'Night',
            };
            const TIME_NAMES = Object.keys(TIME_OF_DAY);

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
                storageKey: 'dayNightGame.settings',
                types: ['facts', 'time'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'facts') {
                        const term = FACT_NAMES[randInt(0, FACT_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: FACTS[term],
                            questionText: "What is '" + term + "'?",
                        };
                    }
                    const activity = TIME_NAMES[randInt(0, TIME_NAMES.length - 1)];
                    return {
                        category: type,
                        correctText: TIME_OF_DAY[activity],
                        questionText: activity + ' — is this usually a day-time or night-time thing?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'facts') {
                        const term = FACT_NAMES.find(function(t) { return FACTS[t] === q.correctText; });
                        const distractors = pickOthers(FACT_NAMES, term, 3).map(function(t) { return FACTS[t]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    return shuffle(['Day', 'Night']);
                },

                hintFor: function(q) {
                    if (q.category === 'facts') {
                        return 'Think about the Earth spinning, facing the Sun, facing away from the Sun, or the Sun appearing and disappearing.';
                    }
                    return 'Would you usually be asleep, or awake and busy, when this happens?';
                },

                explanationFor: function(q) {
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a day and night superstar!",
            });
        })();
    </script>
@endpush
