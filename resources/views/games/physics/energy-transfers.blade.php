@extends('layouts.app')

@section('meta_title', 'Energy Transfers — Kids Physics Game')
@section('meta_blurb', 'A free physics game for kids — match everyday scenarios to the energy transfer taking place, and learn about energy stores.')
@section('meta_words', 'energy transfers game, kids physics game, kinetic energy, potential energy, chemical energy, thermal energy')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-graph-up',
        'title' => 'Energy Transfers',
        'subtitle' => 'Pick your question types, then test your energy knowledge!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'scenarios', 'label' => 'Spot the transfer'],
            ['id' => 'stores', 'label' => 'Energy stores'],
        ],
        'aboutTitle' => 'About this energy transfers game',
        'aboutText' => 'This free physics game covers matching everyday scenarios — like a ball rolling downhill or a toaster toasting bread — to the energy transfer taking place, plus what kinetic, gravitational potential, chemical, thermal and elastic potential energy stores are. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const SCENARIOS = {
                'A ball rolling down a hill': 'Gravitational potential energy transfers to kinetic energy.',
                'A toaster toasting bread': 'Electrical energy transfers to thermal (heat) energy.',
                'A battery-powered torch shining': 'Chemical energy transfers to light and thermal energy.',
                'A stretched elastic band being let go': 'Elastic potential energy transfers to kinetic energy.',
                'Rubbing your hands together': 'Kinetic energy transfers to thermal energy through friction.',
            };
            const SCENARIO_NAMES = Object.keys(SCENARIOS);

            const STORES = {
                'Kinetic energy store': 'Energy an object has because it is moving.',
                'Gravitational potential energy store': 'Energy an object has because of its height above the ground.',
                'Chemical energy store': 'Energy stored in food, fuels and batteries, released by chemical reactions.',
                'Thermal energy store': 'Energy an object has because of its temperature.',
                'Elastic potential energy store': 'Energy stored in a stretched or squashed object, like a spring or elastic band.',
            };
            const STORE_NAMES = Object.keys(STORES);

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
                storageKey: 'energyTransfersGame.settings',
                types: ['scenarios', 'stores'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const pool = type === 'scenarios' ? SCENARIOS : STORES;
                    const names = type === 'scenarios' ? SCENARIO_NAMES : STORE_NAMES;
                    const key = names[randInt(0, names.length - 1)];
                    return {
                        category: type,
                        label: key,
                        correctText: pool[key],
                        questionText: type === 'scenarios' ? (key + ' — what energy transfer happens?') : ("What is a '" + key + "'?"),
                    };
                },

                buildChoices: function(q) {
                    const pool = q.category === 'scenarios' ? SCENARIOS : STORES;
                    const names = q.category === 'scenarios' ? SCENARIO_NAMES : STORE_NAMES;
                    const distractors = pickOthers(names, q.label, 3).map(function(k) { return pool[k]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'scenarios') {
                        return 'Think about what kind of energy the object starts with, and what kind it ends up with.';
                    }
                    return 'Think about movement, height, food and fuel, temperature, or being stretched and squashed.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You're an energy transfers superstar!",
            });
        })();
    </script>
@endpush
