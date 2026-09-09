@extends('layouts.app')

@section('meta_title', 'Circuits — Kids Physics Game')
@section('meta_blurb', 'A free physics game for kids — learn what circuit components do, and tell series circuits apart from parallel circuits.')
@section('meta_words', 'circuits game, kids physics game, series parallel circuit, battery bulb switch resistor ammeter voltmeter')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-lightning-charge',
        'title' => 'Circuits',
        'subtitle' => 'Pick your question types, then test your circuits knowledge!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'components', 'label' => 'Circuit components'],
            ['id' => 'circuitTypes', 'label' => 'Series or parallel?'],
        ],
        'aboutTitle' => 'About this circuits game',
        'aboutText' => 'This free physics game covers what circuit components do — cells, bulbs, switches, resistors, ammeters and voltmeters — plus the difference between series and parallel circuits. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const COMPONENTS = {
                'Cell (battery)': 'Provides the energy that pushes current around a circuit.',
                'Bulb': 'Converts electrical energy into light, and some heat.',
                'Switch': 'Opens or closes a circuit to turn the current off or on.',
                'Resistor': 'Reduces the flow of current in a circuit.',
                'Ammeter': 'Measures the current flowing in a circuit.',
                'Voltmeter': 'Measures the potential difference, or voltage, across a component.',
                'Fuse': 'Melts and breaks the circuit if too much current flows, protecting it from damage.',
                'LED (light-emitting diode)': 'Lights up when current flows through it in one direction only.',
                'Buzzer': 'Converts electrical energy into sound.',
                'Motor': 'Converts electrical energy into kinetic (movement) energy.',
            };
            const COMPONENT_NAMES = Object.keys(COMPONENTS);

            const CIRCUIT_TYPES = {
                'Series circuit': 'Components are connected in a single loop, one after another — if one breaks, the whole circuit stops.',
                'Parallel circuit': 'Components are connected on separate branches — if one breaks, the others can keep working.',
                'Current in a series circuit': 'The current is the same everywhere in the loop, no matter where you measure it.',
                'Current in a parallel circuit': 'The current splits between the branches, so different branches can carry different amounts of current.',
                'Voltage in a series circuit': 'The total voltage from the battery is shared out between all the components in the loop.',
                'Voltage in a parallel circuit': 'The full battery voltage is supplied across each branch.',
                'Adding more bulbs to a series circuit': 'Each bulb gets a smaller share of the voltage, so all the bulbs get dimmer.',
                'Adding more bulbs to a parallel circuit': 'Each bulb still gets the full voltage, so the bulbs stay just as bright.',
                'A switch in a series circuit': 'Opening the switch breaks the single loop, so everything in the circuit turns off.',
                'A switch on one branch of a parallel circuit': 'Opening that switch only turns off the components on that branch — the other branches keep working.',
            };
            const CIRCUIT_TYPE_NAMES = Object.keys(CIRCUIT_TYPES);

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
                storageKey: 'circuitsGame.settings',
                types: ['components', 'circuitTypes'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'components') {
                        const component = COMPONENT_NAMES[randInt(0, COMPONENT_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: COMPONENTS[component],
                            questionText: 'What does a ' + component.toLowerCase() + ' do in a circuit?',
                        };
                    }
                    const circuitType = CIRCUIT_TYPE_NAMES[randInt(0, CIRCUIT_TYPE_NAMES.length - 1)];
                    return {
                        category: type,
                        label: circuitType,
                        correctText: CIRCUIT_TYPES[circuitType],
                        questionText: circuitType + ' — what happens?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'components') {
                        const component = COMPONENT_NAMES.find(function(c) { return COMPONENTS[c] === q.correctText; });
                        const distractors = pickOthers(COMPONENT_NAMES, component, 3).map(function(c) { return COMPONENTS[c]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(CIRCUIT_TYPE_NAMES, q.label, 3).map(function(c) { return CIRCUIT_TYPES[c]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'components') {
                        return 'Think about energy, light, on/off, slowing current, or measuring something.';
                    }
                    return "Think about whether it's one single loop or separate branches, and how that affects current, voltage, brightness or switches.";
                },

                explanationFor: function(q) {
                    if (q.category === 'circuitTypes') return q.label + ': ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a circuits superstar!",
            });
        })();
    </script>
@endpush
