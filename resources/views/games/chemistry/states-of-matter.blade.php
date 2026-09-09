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
                'Honey': 'Liquid',
                'Helium in a balloon': 'Gas',
                'Brick': 'Solid',
            };
            const OBJECT_NAMES = Object.keys(OBJECTS);
            const STATE_LIST = ['Solid', 'Liquid', 'Gas'];

            const FACTS = {
                'Solid': 'Keeps its shape and size — its particles are packed tightly together and only vibrate in place.',
                'Liquid': 'Takes the shape of its container but keeps the same amount of space — its particles can move past each other.',
                'Gas': 'Spreads out to fill any space — its particles move around freely and are far apart.',
                'Melting point': 'The temperature at which a solid turns into a liquid.',
                'Boiling point': 'The temperature at which a liquid turns into a gas throughout the whole liquid, not just at the surface.',
                'Particles in a solid': 'Vibrate in fixed positions but do not move from place to place, which is why a solid keeps its shape.',
                'Particles in a gas': 'Move quickly in random directions and are spread far apart from each other.',
                'Squashing a gas': 'A gas can be compressed into a smaller space quite easily, because its particles are far apart.',
                'Squashing a liquid': 'A liquid is very hard to compress, because its particles are already close together.',
                'Changing state': 'Happens when heating or cooling gives particles more or less energy, changing how they are arranged and how they move.',
            };
            const FACT_NAMES = Object.keys(FACTS);

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
                    const fact = FACT_NAMES[randInt(0, FACT_NAMES.length - 1)];
                    return {
                        category: type,
                        label: fact,
                        correctText: FACTS[fact],
                        questionText: "What is true about '" + fact.toLowerCase() + "'?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'sort') {
                        return shuffle(STATE_LIST.slice());
                    }
                    const distractors = pickOthers(FACT_NAMES, q.label, 3).map(function(f) { return FACTS[f]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'sort') {
                        return 'Can you hold it in your hand and it keeps its shape? Does it flow and pour? Or can you not see or hold it at all?';
                    }
                    return 'Think about whether the particles are packed tight, can slide past each other, or spread far apart, and how that affects the substance.';
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
