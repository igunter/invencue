@extends('layouts.app')

@section('meta_title', 'Chemical vs Physical Changes — Kids Chemistry Game')
@section('meta_blurb', 'A free chemistry game for kids — decide whether a change is chemical or physical, and learn what makes each one different.')
@section('meta_words', 'chemical vs physical changes game, kids chemistry game, reversible change, new substance, rusting burning melting')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-arrow-left-right',
        'title' => 'Chemical vs Physical Changes',
        'subtitle' => 'Pick your question types, then spot the change!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'sort', 'label' => 'Chemical or physical?'],
            ['id' => 'why', 'label' => 'What makes it that type?'],
        ],
        'aboutTitle' => 'About this chemical vs physical changes game',
        'aboutText' => 'This free chemistry game covers deciding whether a change — like melting ice, rusting iron or baking a cake — is chemical or physical, and what actually makes each type different. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const SCENARIOS = {
                'Melting ice': 'Physical change',
                'Boiling water': 'Physical change',
                'Cutting a piece of paper': 'Physical change',
                'Dissolving sugar in water': 'Physical change',
                'Burning wood': 'Chemical change',
                'Rusting iron': 'Chemical change',
                'Baking a cake': 'Chemical change',
                'Mixing vinegar and baking soda': 'Chemical change',
                'Tearing a sheet of paper into pieces': 'Physical change',
                'Freezing water into ice cubes': 'Physical change',
                'Toasting a slice of bread': 'Chemical change',
                'A firework exploding': 'Chemical change',
            };
            const SCENARIO_NAMES = Object.keys(SCENARIOS);

            const WHY = {
                'Physical change': 'Changes how a substance looks or its state, but no new substance is made — it can usually be reversed.',
                'Chemical change': 'Creates one or more new substances with different properties — usually difficult or impossible to reverse.',
                'Reversible change': 'A change that can usually be undone to get the original substance back, such as melting, freezing or dissolving.',
                'Irreversible change': 'A change that is very hard to undo because new substances have formed, such as burning or rusting.',
                'New substance': 'A substance formed during a chemical change that has different properties from what you started with.',
                'Conservation of mass': 'The idea that no atoms are created or destroyed in a change, so the total mass stays the same before and after.',
                'Energy change': 'Heat, light or sound is often given out or taken in during a chemical change, such as burning or an exploding firework.',
                'State change': 'A physical change where a substance moves between solid, liquid and gas, such as melting or evaporating, without becoming a new substance.',
                'Sign of a chemical change': 'A clue such as a colour change, gas bubbles, a temperature change or a new smell that shows a reaction has happened.',
                'Precipitate': 'An insoluble solid that forms and settles out when two solutions react together, often a sign of a chemical change.',
            };
            const WHY_NAMES = Object.keys(WHY);

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
                storageKey: 'chemicalPhysicalChangesGame.settings',
                types: ['sort', 'why'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'sort') {
                        const scenario = SCENARIO_NAMES[randInt(0, SCENARIO_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: SCENARIOS[scenario],
                            questionText: scenario + ' — is this a chemical change or a physical change?',
                        };
                    }
                    const term = WHY_NAMES[randInt(0, WHY_NAMES.length - 1)];
                    return {
                        category: type,
                        label: term,
                        correctText: WHY[term],
                        questionText: "What does '" + term + "' mean?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'sort') {
                        return shuffle(['Physical change', 'Chemical change']);
                    }
                    const distractors = pickOthers(WHY_NAMES, q.label, 3).map(function(t) { return WHY[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'sort') {
                        return 'Ask: could you easily undo this and get the original substance back? If yes, it is usually physical.';
                    }
                    return 'Think about whether this is about undoing a change, a new substance forming, energy, mass, or a visible clue.';
                },

                explanationFor: function(q) {
                    if (q.category === 'why') return q.label + ': ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a chemical vs physical changes superstar!",
            });
        })();
    </script>
@endpush
