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
            };
            const SCENARIO_NAMES = Object.keys(SCENARIOS);

            const WHY = {
                'Physical change': 'Changes how a substance looks or its state, but no new substance is made — it can usually be reversed.',
                'Chemical change': 'Creates one or more new substances with different properties — usually difficult or impossible to reverse.',
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
                    const changeType = randInt(0, 1) === 0 ? 'Physical change' : 'Chemical change';
                    return {
                        category: type,
                        label: changeType,
                        correctText: WHY[changeType],
                        questionText: "What makes something a '" + changeType + "'?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'sort') {
                        return shuffle(['Physical change', 'Chemical change']);
                    }
                    return shuffle([WHY['Physical change'], WHY['Chemical change']]);
                },

                hintFor: function(q) {
                    if (q.category === 'sort') {
                        return 'Ask: could you easily undo this and get the original substance back? If yes, it is usually physical.';
                    }
                    return 'Think about whether a brand new substance is formed, or whether it is the same substance in a different form.';
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
