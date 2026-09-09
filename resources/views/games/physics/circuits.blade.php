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
            };
            const COMPONENT_NAMES = Object.keys(COMPONENTS);

            const CIRCUIT_TYPES = {
                'Series circuit': 'Components are connected in a single loop, one after another — if one breaks, the whole circuit stops.',
                'Parallel circuit': 'Components are connected on separate branches — if one breaks, the others can keep working.',
            };

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
                    const circuitType = randInt(0, 1) === 0 ? 'Series circuit' : 'Parallel circuit';
                    return {
                        category: type,
                        label: circuitType,
                        correctText: CIRCUIT_TYPES[circuitType],
                        questionText: "What happens in a '" + circuitType + "'?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'components') {
                        const component = COMPONENT_NAMES.find(function(c) { return COMPONENTS[c] === q.correctText; });
                        const distractors = pickOthers(COMPONENT_NAMES, component, 3).map(function(c) { return COMPONENTS[c]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    return shuffle([CIRCUIT_TYPES['Series circuit'], CIRCUIT_TYPES['Parallel circuit']]);
                },

                hintFor: function(q) {
                    if (q.category === 'components') {
                        return 'Think about energy, light, on/off, slowing current, or measuring something.';
                    }
                    return 'Think about whether there is one loop, or several separate branches.';
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
