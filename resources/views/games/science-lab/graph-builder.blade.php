@extends('layouts.app')

@section('meta_title', 'Graph Builder — Science Game')
@section('meta_blurb', 'A free science game for school kids — plot and read simple line and bar graphs from a set of results.')
@section('meta_words', 'graph builder game, science game, line graph, bar graph, reading a graph, plotting results, working scientifically')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-graph-up',
        'title' => 'Graph Builder',
        'subtitle' => 'Pick your question types, then read the graph!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'axisChoice', 'label' => 'Choosing axes'],
            ['id' => 'readPoint', 'label' => 'Read a point'],
            ['id' => 'chooseType', 'label' => 'Line or bar?'],
        ],
        'aboutTitle' => 'About this graph builder game',
        'aboutText' => 'This free science game helps school kids practise the skills needed to build and read graphs from a set of results — choosing which variable goes on which axis, reading a value off a simple graph, and deciding whether a line graph or bar graph fits the data. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const AXIS_SCENARIOS = [
                { independent: 'Time (minutes)', dependent: 'Temperature (°C)' },
                { independent: 'Distance from the light source (cm)', dependent: 'Light level (lux)' },
                { independent: 'Number of weights added', dependent: 'Spring length (cm)' },
                { independent: 'Concentration of acid (mol/dm³)', dependent: 'Reaction time (seconds)' },
                { independent: 'Age of the plant (weeks)', dependent: 'Plant height (cm)' },
                { independent: 'Mass added to a spring (g)', dependent: 'Extension of the spring (cm)' },
                { independent: 'Volume of water added (ml)', dependent: 'Height of water in the container (cm)' },
                { independent: 'Number of turns on a wire coil', dependent: 'Strength of the electromagnet (N)' },
                { independent: 'Time in the oven (minutes)', dependent: 'Temperature of the cake mixture (°C)' },
                { independent: 'Force applied to a wire (N)', dependent: 'Length of the stretched wire (mm)' },
            ];

            const GRAPH_SERIES = [
                { label: 'Temperature over 5 minutes', xUnit: 'min', points: [[0, 20], [1, 25], [2, 30], [3, 35], [4, 40]] },
                { label: 'Distance travelled over time', xUnit: 's', points: [[0, 0], [1, 5], [2, 10], [3, 15], [4, 20]] },
                { label: 'Spring length as weights are added', xUnit: 'weights', points: [[0, 10], [1, 12], [2, 14], [3, 16], [4, 18]] },
                { label: 'Plant height over weeks', xUnit: 'weeks', points: [[0, 2], [1, 4], [2, 7], [3, 9], [4, 11]] },
                { label: 'Spring extension as mass is added', xUnit: 'g', points: [[0, 0], [1, 3], [2, 6], [3, 9], [4, 12]] },
                { label: 'Water level as water is poured in', xUnit: 'litres', points: [[0, 1], [1, 3], [2, 5], [3, 7], [4, 9]] },
                { label: 'Cooling of a hot drink over time', xUnit: 'min', points: [[0, 80], [1, 70], [2, 62], [3, 55], [4, 49]] },
                { label: 'Speed of a ball rolling down a ramp', xUnit: 's', points: [[0, 0], [1, 2], [2, 4], [3, 6], [4, 8]] },
                { label: 'Mass of salt dissolved over time', xUnit: 'min', points: [[0, 0], [1, 5], [2, 9], [3, 12], [4, 14]] },
                { label: 'Gas volume produced over time', xUnit: 's', points: [[0, 0], [1, 10], [2, 18], [3, 24], [4, 28]] },
            ];

            const TYPE_SCENARIOS = [
                { scenario: 'The temperature of water measured every minute as it cools', correctText: 'Line graph' },
                { scenario: 'The height of a plant measured every day for two weeks', correctText: 'Line graph' },
                { scenario: 'The favourite colour chosen by each student in a class', correctText: 'Bar graph' },
                { scenario: 'The number of pupils who chose each type of pet as their favourite', correctText: 'Bar graph' },
                { scenario: 'The distance a toy car travels each second as it rolls down a ramp', correctText: 'Line graph' },
                { scenario: 'The mass of four different rock samples', correctText: 'Bar graph' },
                { scenario: 'The volume of gas produced every 10 seconds during a reaction', correctText: 'Line graph' },
                { scenario: 'The number of pupils travelling to school by each method of transport', correctText: 'Bar graph' },
                { scenario: 'The extension of a spring as increasing masses are added', correctText: 'Line graph' },
                { scenario: 'The average rainfall recorded in each of four different cities', correctText: 'Bar graph' },
            ];

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
                storageKey: 'graphBuilderGame.settings',
                types: ['axisChoice', 'readPoint', 'chooseType'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 25,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'axisChoice') {
                        const scenario = AXIS_SCENARIOS[randInt(0, AXIS_SCENARIOS.length - 1)];
                        const askX = Math.random() < 0.5;
                        return {
                            category: type,
                            scenario: scenario,
                            askX: askX,
                            correctText: askX ? scenario.independent : scenario.dependent,
                            questionText: 'For this investigation — independent variable: ' + scenario.independent + ', dependent variable: ' + scenario.dependent + ' — what goes on the ' + (askX ? 'x-axis (horizontal)' : 'y-axis (vertical)') + '?',
                        };
                    }

                    if (type === 'readPoint') {
                        const series = GRAPH_SERIES[randInt(0, GRAPH_SERIES.length - 1)];
                        const point = series.points[randInt(1, series.points.length - 1)];
                        return {
                            category: type,
                            series: series,
                            correctText: point[1] + '',
                            questionText: series.label + ' — the graph shows a value of ' + point[1] + ' at ' + point[0] + ' ' + series.xUnit + '. What does the graph read at this point?',
                        };
                    }

                    const item = TYPE_SCENARIOS[randInt(0, TYPE_SCENARIOS.length - 1)];
                    return {
                        category: type,
                        item: item,
                        correctText: item.correctText,
                        questionText: item.scenario + ' — is this best shown as a line graph or a bar graph?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'axisChoice') {
                        const others = pickOthers(AXIS_SCENARIOS, q.scenario, 3);
                        const distractors = others.map(function(s) { return q.askX ? s.independent : s.dependent; });
                        return shuffle([q.correctText].concat(distractors));
                    }

                    if (q.category === 'readPoint') {
                        const correctValue = parseInt(q.correctText, 10);
                        const pool = [correctValue - 4, correctValue - 2, correctValue + 2, correctValue + 4, correctValue + 6].filter(function(v) { return v >= 0; });
                        const distractors = pickOthers(pool, correctValue, 3).map(function(v) { return v + ''; });
                        return shuffle([q.correctText].concat(distractors));
                    }

                    return shuffle(['Line graph', 'Bar graph']);
                },

                hintFor: function(q) {
                    if (q.category === 'axisChoice') {
                        return 'The independent variable (what you change) always goes on the x-axis, and the dependent variable (what you measure) goes on the y-axis.';
                    }
                    if (q.category === 'readPoint') {
                        return 'Find the point on the graph and read straight across to the y-axis.';
                    }
                    return 'A line graph works for continuous data that changes over something like time. A bar graph works for separate categories.';
                },

                explanationFor: function(q) {
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a graph building superstar!",
            });
        })();
    </script>
@endpush
