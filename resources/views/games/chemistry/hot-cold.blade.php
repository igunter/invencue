@extends('layouts.app')

@section('meta_title', 'Hot & Cold — Kids Chemistry Game')
@section('meta_blurb', 'A free chemistry game for young kids — learn what happens when things melt, freeze, evaporate and condense.')
@section('meta_words', 'hot and cold game, kids chemistry game, melting freezing evaporating condensing, changing state for kids')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-sliders',
        'title' => 'Hot & Cold',
        'subtitle' => 'Pick your question types, then test your changing-state knowledge!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'changes', 'label' => 'What happens?'],
            ['id' => 'examples', 'label' => 'Spot the change'],
        ],
        'aboutTitle' => 'About this hot & cold game',
        'aboutText' => 'This free chemistry game helps young kids learn what happens when things melt, freeze, evaporate and condense as they heat up or cool down. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const CHANGES = {
                'Melting': 'A solid turns into a liquid when it gets warm enough, like ice turning to water.',
                'Freezing': 'A liquid turns into a solid when it gets cold enough, like water turning to ice.',
                'Evaporating': 'A liquid turns into a gas when it gets warm, like a puddle drying up in the sun.',
                'Condensing': 'A gas turns into a liquid when it cools down, like steam turning into droplets on a cold window.',
            };
            const CHANGE_NAMES = Object.keys(CHANGES);

            const EXAMPLES = {
                'An ice cube left out in the sun': 'Melting',
                'A bottle of water left in the freezer overnight': 'Freezing',
                'A puddle disappearing on a hot, sunny day': 'Evaporating',
                'Steam from a hot shower turning into droplets on a mirror': 'Condensing',
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
                storageKey: 'hotColdGame.settings',
                types: ['changes', 'examples'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'changes') {
                        const change = CHANGE_NAMES[randInt(0, CHANGE_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: CHANGES[change],
                            questionText: "What happens when something is '" + change + "'?",
                        };
                    }
                    const example = EXAMPLE_NAMES[randInt(0, EXAMPLE_NAMES.length - 1)];
                    return {
                        category: type,
                        correctText: EXAMPLES[example],
                        questionText: example + ' — which change is this?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'changes') {
                        const change = CHANGE_NAMES.find(function(c) { return CHANGES[c] === q.correctText; });
                        const distractors = pickOthers(CHANGE_NAMES, change, 3).map(function(c) { return CHANGES[c]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(CHANGE_NAMES, q.correctText, 3);
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    return 'Think about whether something is turning from solid to liquid, liquid to solid, liquid to gas, or gas to liquid.';
                },

                explanationFor: function(q) {
                    if (q.category === 'changes') return q.correctText;
                    return q.correctText + ': ' + CHANGES[q.correctText];
                },

                masteryMessage: "Amazing! You're a hot and cold superstar!",
            });
        })();
    </script>
@endpush
