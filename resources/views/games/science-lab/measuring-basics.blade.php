@extends('layouts.app')

@section('meta_title', 'Measuring Basics — Kids Science Game')
@section('meta_blurb', 'A free science game for young kids — read a simple scale on a ruler, jug or thermometer, and pick the right tool for the job.')
@section('meta_words', 'measuring game, kids science game, reading a ruler, reading a thermometer, reading a jug, choosing the right equipment')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-rulers',
        'title' => 'Measuring Basics',
        'subtitle' => 'Pick your question types, then take a measurement!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'readScale', 'label' => 'Read the scale'],
            ['id' => 'chooseTool', 'label' => 'Choose the tool'],
        ],
        'aboutTitle' => 'About this measuring basics game',
        'aboutText' => 'This free science game helps young kids practise reading simple scales on a ruler, jug or thermometer, and choosing the right piece of equipment to measure length, volume, temperature or mass. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const SCALES = [
                { tool: 'ruler', unit: 'cm', min: 0, max: 30, step: 1 },
                { tool: 'measuring jug', unit: 'ml', min: 0, max: 500, step: 50 },
                { tool: 'thermometer', unit: '°C', min: -10, max: 40, step: 5 },
            ];

            const TOOLS = {
                'Length of a pencil': 'Ruler',
                'Volume of juice in a cup': 'Measuring jug',
                'Temperature of bath water': 'Thermometer',
                'Mass of an apple': 'Weighing scales',
                'How long a table is': 'Ruler',
                'How hot a cup of tea is': 'Thermometer',
                'How much water is in a bottle': 'Measuring jug',
                'How heavy a bag of flour is': 'Weighing scales',
            };
            const TOOL_TASKS = Object.keys(TOOLS);
            const TOOL_LIST = ['Ruler', 'Measuring jug', 'Thermometer', 'Weighing scales'];

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
                storageKey: 'measuringBasicsGame.settings',
                types: ['readScale', 'chooseTool'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 25,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'readScale') {
                        const scale = SCALES[randInt(0, SCALES.length - 1)];
                        const steps = Math.round((scale.max - scale.min) / scale.step);
                        const value = scale.min + scale.step * randInt(1, steps - 1);
                        return {
                            category: type,
                            scale: scale,
                            correctText: value + ' ' + scale.unit,
                            questionText: 'The pointer on the ' + scale.tool + ' is at ' + value + ' ' + scale.unit + '. What is this reading?',
                        };
                    }

                    const task = TOOL_TASKS[randInt(0, TOOL_TASKS.length - 1)];
                    return {
                        category: type,
                        correctText: TOOLS[task],
                        questionText: 'Which piece of equipment would you use to measure: ' + task.toLowerCase() + '?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'readScale') {
                        const scale = q.scale;
                        const steps = Math.round((scale.max - scale.min) / scale.step);
                        const correctValue = parseFloat(q.correctText);
                        const pool = [];
                        for (let i = 0; i <= steps; i++) {
                            pool.push(scale.min + scale.step * i);
                        }
                        const distractors = pickOthers(pool, correctValue, 3).map(function(v) { return v + ' ' + scale.unit; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(TOOL_LIST, q.correctText, 3);
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'readScale') {
                        return 'Read the number the pointer or liquid level lines up with, and don\'t forget the unit.';
                    }
                    return 'Think about whether you need to measure length, volume, temperature or mass.';
                },

                explanationFor: function(q) {
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a measuring superstar!",
            });
        })();
    </script>
@endpush
