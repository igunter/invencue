@extends('layouts.app')

@section('meta_title', 'States of Matter — Kids Chemistry Game')
@section('meta_blurb', 'A free chemistry game for young kids — sort everyday things into solids, liquids and gases, and learn what makes each state special.')
@section('meta_words', 'states of matter game, kids chemistry game, solid liquid gas, particles, learn chemistry')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-droplet-half',
        'title' => 'States of Matter',
        'subtitle' => 'Pick your question types, then sort those states!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'sort', 'label' => 'Solid, liquid or gas?'],
            ['id' => 'facts', 'label' => 'State facts'],
        ],
        'aboutTitle' => 'About this states of matter game',
        'aboutText' => 'This free chemistry game helps young kids learn to sort everyday things into solids, liquids and gases, and what makes each state special. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const OBJECTS = {
                'Ice cube': 'Solid',
                'Rock': 'Solid',
                'Wood': 'Solid',
                'Water': 'Liquid',
                'Juice': 'Liquid',
                'Milk': 'Liquid',
                'Steam': 'Gas',
                'Air': 'Gas',
                'Oxygen': 'Gas',
            };
            const OBJECT_NAMES = Object.keys(OBJECTS);
            const STATE_LIST = ['Solid', 'Liquid', 'Gas'];

            const FACTS = {
                'Solid': 'Keeps its shape and size — its particles are packed tightly together.',
                'Liquid': 'Takes the shape of its container — its particles can move past each other.',
                'Gas': 'Spreads out to fill any space — its particles move around freely and are far apart.',
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

            window.ScienceQuiz.run({
                storageKey: 'statesOfMatterGame.settings',
                types: ['sort', 'facts'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'sort') {
                        const object = OBJECT_NAMES[randInt(0, OBJECT_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: OBJECTS[object],
                            questionText: 'Is ' + object.toLowerCase() + ' a solid, a liquid, or a gas?',
                        };
                    }
                    const state = STATE_LIST[randInt(0, STATE_LIST.length - 1)];
                    return {
                        category: type,
                        label: state,
                        correctText: FACTS[state],
                        questionText: 'What is special about a ' + state.toLowerCase() + '?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'sort') {
                        return shuffle(STATE_LIST.slice());
                    }
                    const others = STATE_LIST.filter(function(s) { return s !== q.label; }).map(function(s) { return FACTS[s]; });
                    const bonusDistractor = 'Only exists inside stars and lightning — far too hot for everyday particles to stay together.';
                    return shuffle([q.correctText].concat(others).concat([bonusDistractor]));
                },

                hintFor: function(q) {
                    if (q.category === 'sort') {
                        return 'Can you hold it in your hand and it keeps its shape? Does it flow and pour? Or can you not see or hold it at all?';
                    }
                    return 'Think about whether the particles are packed tight, can slide past each other, or spread far apart.';
                },

                explanationFor: function(q) {
                    if (q.category === 'facts') return q.label + ': ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a states of matter superstar!",
            });
        })();
    </script>
@endpush
