@extends('layouts.app')

@section('meta_title', 'Weather & Climate — Kids Earth Science Game')
@section('meta_blurb', 'A free earth science game for kids — learn the difference between weather and climate, and practise reading simple temperature data.')
@section('meta_words', 'weather and climate game, kids earth science game, temperature rainfall climate zone, reading weather data')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-graph-up',
        'title' => 'Weather & Climate',
        'subtitle' => 'Pick your question types, then test your weather knowledge!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'terms', 'label' => 'Key terms'],
            ['id' => 'dataRead', 'label' => 'Reading weather data'],
        ],
        'aboutTitle' => 'About this weather & climate game',
        'aboutText' => 'This free earth science game covers the difference between weather and climate, plus temperature, rainfall and climate zones, and practises reading simple temperature data. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Weather': "The day-to-day conditions in a place, like today's temperature or rainfall.",
                'Climate': 'The average weather pattern in a place over many years.',
                'Temperature': 'A measure of how hot or cold something is.',
                'Rainfall': 'The amount of rain that falls in a place over a period of time, often measured in millimetres.',
                'Climate zone': 'A region of the world with a similar climate pattern, like tropical, temperate or polar.',
            };
            const TERM_NAMES = Object.keys(TERMS);
            const TEMP_POOL = [8, 10, 12, 14, 16, 18, 20, 22, 24];

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

            function round1(n) {
                return Math.round(n * 10) / 10;
            }

            function buildTempQuestion() {
                for (let attempt = 0; attempt < 10; attempt++) {
                    const values = [];
                    for (let i = 0; i < 4; i++) {
                        values.push(TEMP_POOL[randInt(0, TEMP_POOL.length - 1)]);
                    }
                    const total = values.reduce(function(a, b) { return a + b; }, 0);
                    const mean = round1(total / 4);
                    if (values.indexOf(mean) === -1) {
                        return { values: values, total: total, mean: mean };
                    }
                }
                return { values: [10, 12, 14, 16], total: 52, mean: 13 };
            }

            window.ScienceQuiz.run({
                storageKey: 'weatherClimateGame.settings',
                types: ['terms', 'dataRead'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 25,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'terms') {
                        const term = TERM_NAMES[randInt(0, TERM_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: TERMS[term],
                            questionText: "What is '" + term + "'?",
                        };
                    }
                    const q = buildTempQuestion();
                    return {
                        category: type,
                        values: q.values,
                        total: q.total,
                        correctText: String(q.mean) + ' °C',
                        questionText: 'A city recorded these temperatures over 4 days: ' + q.values.join('°C, ') + '°C. What was the average temperature?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'terms') {
                        const term = TERM_NAMES.find(function(t) { return TERMS[t] === q.correctText; });
                        const distractors = pickOthers(TERM_NAMES, term, 3).map(function(t) { return TERMS[t]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const wrongSum = q.total + ' °C';
                    const wrongDivBy3 = round1(q.total / 3) + ' °C';
                    const wrongOneValue = q.values[randInt(0, q.values.length - 1)] + ' °C';
                    return shuffle([q.correctText, wrongSum, wrongDivBy3, wrongOneValue]);
                },

                hintFor: function(q) {
                    if (q.category === 'terms') {
                        return 'Think about today vs. many years, hot/cold, rain amount, or a region of the world.';
                    }
                    return 'Add up all four temperatures, then divide by 4.';
                },

                explanationFor: function(q) {
                    if (q.category === 'dataRead') return q.values.join(' + ') + ' = ' + q.total + ', ÷ 4 = ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a weather and climate superstar!",
            });
        })();
    </script>
@endpush
