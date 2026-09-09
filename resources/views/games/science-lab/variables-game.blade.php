@extends('layouts.app')

@section('meta_title', 'Variables Game — Science Game')
@section('meta_blurb', 'A free science game for school kids — identify the independent, dependent and control variables in an experiment.')
@section('meta_words', 'variables game, science game, independent variable, dependent variable, control variable, working scientifically')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-list-check',
        'title' => 'Variables Game',
        'subtitle' => 'Pick your question types, then spot the variable!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'identify', 'label' => 'Identify the variable'],
            ['id' => 'define', 'label' => 'Match the definition'],
        ],
        'aboutTitle' => 'About this variables game',
        'aboutText' => 'This free science game helps school kids identify the independent variable (what you change), the dependent variable (what you measure) and the control variables (what you keep the same) in a range of experiments. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const EXPERIMENTS = [
                {
                    scenario: 'Investigating how the amount of sunlight affects plant growth',
                    independent: 'Amount of sunlight',
                    dependent: 'Plant growth (height)',
                    control: 'Amount of water and type of soil',
                },
                {
                    scenario: 'Investigating how the ramp angle affects how far a toy car travels',
                    independent: 'Ramp angle',
                    dependent: 'Distance travelled',
                    control: 'The car used and the surface it rolls on',
                },
                {
                    scenario: 'Investigating how water temperature affects how fast sugar dissolves',
                    independent: 'Water temperature',
                    dependent: 'Time for sugar to dissolve',
                    control: 'Amount of sugar and amount of water',
                },
                {
                    scenario: 'Investigating how the number of batteries affects the brightness of a bulb',
                    independent: 'Number of batteries',
                    dependent: 'Brightness of the bulb',
                    control: 'The bulb and wires used',
                },
                {
                    scenario: 'Investigating how the mass of a pendulum bob affects its swing time',
                    independent: 'Mass of the pendulum bob',
                    dependent: 'Time for a swing',
                    control: 'Length of the string and starting angle',
                },
                {
                    scenario: 'Investigating how fertiliser amount affects tomato plant height',
                    independent: 'Amount of fertiliser',
                    dependent: 'Tomato plant height',
                    control: 'Amount of water and sunlight',
                },
            ];

            const DEFINITIONS = {
                'Independent variable': 'The one thing you deliberately change in an experiment.',
                'Dependent variable': 'The thing you measure to see the effect of the change.',
                'Control variable': 'Something you keep the same so the test stays fair.',
            };
            const DEFINITION_NAMES = Object.keys(DEFINITIONS);
            const BONUS_DEFINITION = 'A prediction you make before starting the experiment.';

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
                storageKey: 'variablesGame.settings',
                types: ['identify', 'define'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 25,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'identify') {
                        const experiment = EXPERIMENTS[randInt(0, EXPERIMENTS.length - 1)];
                        const kinds = [
                            { name: 'independent', label: 'independent variable (what is changed)' },
                            { name: 'dependent', label: 'dependent variable (what is measured)' },
                            { name: 'control', label: 'control variable (what is kept the same)' },
                        ];
                        const kind = kinds[randInt(0, kinds.length - 1)];
                        return {
                            category: type,
                            experiment: experiment,
                            kind: kind.name,
                            correctText: experiment[kind.name],
                            questionText: experiment.scenario + ' — what is the ' + kind.label + '?',
                        };
                    }

                    const term = DEFINITION_NAMES[randInt(0, DEFINITION_NAMES.length - 1)];
                    return {
                        category: type,
                        label: term,
                        correctText: DEFINITIONS[term],
                        questionText: 'Which definition matches: "' + term + '"?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'identify') {
                        const otherExperiments = pickOthers(EXPERIMENTS, q.experiment, 3);
                        const distractors = otherExperiments.map(function(e) { return e[q.kind]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(DEFINITION_NAMES, q.label, 2).map(function(d) { return DEFINITIONS[d]; });
                    return shuffle([q.correctText].concat(distractors).concat([BONUS_DEFINITION]));
                },

                hintFor: function(q) {
                    if (q.category === 'identify') {
                        return 'The independent variable is changed on purpose, the dependent variable is measured, and control variables are kept the same.';
                    }
                    return 'Think about what is changed, what is measured, and what is kept the same.';
                },

                explanationFor: function(q) {
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a variables superstar!",
            });
        })();
    </script>
@endpush
