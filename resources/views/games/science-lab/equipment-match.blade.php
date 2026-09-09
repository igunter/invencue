@extends('layouts.app')

@section('meta_title', 'Equipment Match — Science Game')
@section('meta_blurb', 'A free science game for school kids — match each piece of lab equipment to what it is used for.')
@section('meta_words', 'equipment match game, science game, lab equipment quiz, bunsen burner, measuring cylinder, working scientifically')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-clipboard',
        'title' => 'Equipment Match',
        'subtitle' => 'Pick your question types, then match the equipment!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'usedFor', 'label' => 'What is it used for?'],
            ['id' => 'whichTool', 'label' => 'Which tool would you use?'],
        ],
        'aboutTitle' => 'About this equipment match game',
        'aboutText' => 'This free science game helps school kids match common lab equipment — such as a Bunsen burner, measuring cylinder, thermometer and microscope — to what it is used for, and choose the right tool for a given task. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const EQUIPMENT = {
                'Bunsen burner': 'Heats substances using a gas flame.',
                'Measuring cylinder': 'Measures the volume of a liquid accurately.',
                'Thermometer': 'Measures the temperature of a substance.',
                'Microscope': 'Magnifies very small objects such as cells.',
                'Balance': 'Measures the mass of an object.',
                'Test tube': 'Holds small amounts of liquid or solid for reactions.',
                'Tripod and gauze': 'Supports a container while it is being heated.',
                'Pipette': 'Transfers a small, precise amount of liquid.',
                'Newton meter': 'Measures the force applied to an object.',
                'Stopwatch': 'Measures how much time has passed.',
            };
            const EQUIPMENT_NAMES = Object.keys(EQUIPMENT);

            const TASKS = {
                'Find out how much a rock sample weighs': 'Balance',
                'See the structure of an onion cell': 'Microscope',
                'Measure exactly 25ml of a liquid': 'Measuring cylinder',
                'Check the temperature of boiling water': 'Thermometer',
                'Heat a substance in a test tube': 'Bunsen burner',
                'Time how long a reaction takes': 'Stopwatch',
                'Measure the force needed to pull a block': 'Newton meter',
                'Add three drops of a chemical to a test tube': 'Pipette',
                'Support a beaker while it is being heated over a Bunsen burner': 'Tripod and gauze',
                'See how long an experiment takes to finish': 'Stopwatch',
            };
            const TASK_NAMES = Object.keys(TASKS);

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
                storageKey: 'equipmentMatchGame.settings',
                types: ['usedFor', 'whichTool'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'usedFor') {
                        const equipment = EQUIPMENT_NAMES[randInt(0, EQUIPMENT_NAMES.length - 1)];
                        return {
                            category: type,
                            label: equipment,
                            correctText: EQUIPMENT[equipment],
                            questionText: 'What is a ' + equipment.toLowerCase() + ' used for?',
                        };
                    }

                    const task = TASK_NAMES[randInt(0, TASK_NAMES.length - 1)];
                    return {
                        category: type,
                        correctText: TASKS[task],
                        questionText: task + ' — which piece of equipment would you use?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'usedFor') {
                        const distractors = pickOthers(EQUIPMENT_NAMES, q.label, 3).map(function(e) { return EQUIPMENT[e]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(EQUIPMENT_NAMES, q.correctText, 3);
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'usedFor') {
                        return 'Think about what job this piece of equipment is designed to do.';
                    }
                    return 'Think about whether you need to measure, heat, magnify or time something.';
                },

                explanationFor: function(q) {
                    if (q.category === 'usedFor') return q.label + ': ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're an equipment matching superstar!",
            });
        })();
    </script>
@endpush
