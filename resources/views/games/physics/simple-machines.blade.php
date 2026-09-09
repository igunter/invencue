@extends('layouts.app')

@section('meta_title', 'Simple Machines — Kids Physics Game')
@section('meta_blurb', 'A free physics game for young kids — learn what levers, wheels, ramps, pulleys and screws do, and spot them in everyday objects.')
@section('meta_words', 'simple machines game, kids physics game, lever wheel ramp pulley screw, learn simple machines')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-arrows-expand',
        'title' => 'Simple Machines',
        'subtitle' => 'Pick your question types, then spot those machines!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'machines', 'label' => 'What does it do?'],
            ['id' => 'examples', 'label' => 'Spot the machine'],
        ],
        'aboutTitle' => 'About this simple machines game',
        'aboutText' => 'This free physics game helps young kids learn what levers, wheels and axles, ramps, pulleys and screws do, and spot them being used in everyday objects like seesaws, wheelbarrows and jar lids. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const MACHINES = {
                'Lever': 'A bar that pivots on a fixed point, used to lift or move things with less effort.',
                'Wheel and axle': 'A round wheel attached to a rod, making it easier to move things or turn something.',
                'Ramp': 'A slanted surface that makes it easier to move things up or down.',
                'Pulley': 'A wheel with a rope over it, used to lift things by changing the direction of the pull.',
                'Screw': 'A spiral-shaped ramp wrapped around a rod, used to hold things together or lift materials.',
                'Wedge': 'A tool with a thick end and a thin, sharp edge, used to split or cut things apart.',
                'Gear': 'A wheel with teeth that locks together with another gear to change speed or force.',
                'Spring': 'A coiled piece of metal that stores energy when squashed or stretched, then pushes or pulls back.',
                'Cam': 'A rotating, oddly-shaped wheel that changes turning motion into up-and-down motion.',
                'Block and tackle': 'A system of pulleys used together to lift very heavy loads with much less effort.',
            };
            const MACHINE_NAMES = Object.keys(MACHINES);

            const EXAMPLES = {
                'A seesaw': 'Lever',
                'A wheelbarrow': 'Lever',
                'A skateboard': 'Wheel and axle',
                'A slide at the playground': 'Ramp',
                'A flagpole rope system': 'Pulley',
                'A jar lid': 'Screw',
                'A pair of scissors': 'Wedge',
                'A bicycle': 'Gear',
                'A trampoline': 'Spring',
                'A crane lifting heavy loads': 'Block and tackle',
            };
            const EXAMPLE_NAMES = Object.keys(EXAMPLES);

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
                storageKey: 'simpleMachinesGame.settings',
                types: ['machines', 'examples'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'machines') {
                        const machine = MACHINE_NAMES[randInt(0, MACHINE_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: MACHINES[machine],
                            questionText: 'What does a ' + machine.toLowerCase() + ' do?',
                        };
                    }
                    const example = EXAMPLE_NAMES[randInt(0, EXAMPLE_NAMES.length - 1)];
                    return {
                        category: type,
                        correctText: EXAMPLES[example],
                        questionText: example + ' — which simple machine is this?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'machines') {
                        const machine = MACHINE_NAMES.find(function(m) { return MACHINES[m] === q.correctText; });
                        const distractors = pickOthers(MACHINE_NAMES, machine, 3).map(function(m) { return MACHINES[m]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(MACHINE_NAMES, q.correctText, 3);
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'machines') {
                        return 'Think about pivoting, rolling, sloping, pulling a rope, or spiralling.';
                    }
                    return 'Think about whether it tips, rolls, slopes, uses a rope, or twists.';
                },

                explanationFor: function(q) {
                    if (q.category === 'examples') return q.correctText + ': ' + MACHINES[q.correctText];
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a simple machines superstar!",
            });
        })();
    </script>
@endpush
