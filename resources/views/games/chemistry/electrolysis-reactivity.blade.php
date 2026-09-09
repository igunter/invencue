@extends('layouts.app')

@section('meta_title', 'Electrolysis & Reactivity — GCSE Chemistry Game')
@section('meta_blurb', 'A free GCSE chemistry game covering the reactivity series, displacement reactions, and electrolysis at the cathode and anode.')
@section('meta_words', 'electrolysis game, reactivity series game, gcse chemistry game, displacement reaction, cathode, anode, electrolyte')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-lightning-charge',
        'title' => 'Electrolysis & Reactivity',
        'subtitle' => 'Pick your question types, then test your knowledge!',
        'typeToggles' => [
            ['id' => 'reactivity', 'label' => 'Reactivity series'],
            ['id' => 'electrolysis', 'label' => 'Electrolysis'],
        ],
        'aboutTitle' => 'About this electrolysis & reactivity game',
        'aboutText' => 'This free GCSE chemistry game covers the reactivity series and displacement reactions, plus what happens at the cathode and anode during electrolysis. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const REACTIVITY = {
                'Reactivity series': 'A list of metals ordered from most to least reactive.',
                'Displacement reaction': 'A reaction where a more reactive metal takes the place of a less reactive metal in a compound.',
                'Magnesium added to copper sulfate solution': 'Magnesium displaces copper from the solution, because magnesium is more reactive.',
                'Zinc added to iron sulfate solution': 'Zinc displaces iron from the solution, because zinc is more reactive.',
                'Gold added to dilute acid': 'No reaction happens — gold is far too unreactive.',
            };
            const REACTIVITY_NAMES = Object.keys(REACTIVITY);

            const ELECTROLYSIS = {
                'Cathode (negative electrode)': 'Positive ions move here and gain electrons — this is reduction.',
                'Anode (positive electrode)': 'Negative ions move here and lose electrons — this is oxidation.',
                'Electrolyte': 'A liquid or molten substance that conducts electricity because it contains free-moving ions.',
                'Electrolysis of molten lead bromide': 'Lead forms at the cathode; orange bromine gas forms at the anode.',
                'Electrolysis of copper sulfate solution with copper electrodes': 'Copper is deposited on the cathode while the anode dissolves — this is used to purify copper.',
            };
            const ELECTROLYSIS_NAMES = Object.keys(ELECTROLYSIS);

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

            function poolFor(category) {
                return category === 'reactivity' ? REACTIVITY : ELECTROLYSIS;
            }

            window.ScienceQuiz.run({
                storageKey: 'electrolysisReactivityGame.settings',
                types: ['reactivity', 'electrolysis'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const names = type === 'reactivity' ? REACTIVITY_NAMES : ELECTROLYSIS_NAMES;
                    const pool = poolFor(type);
                    const key = names[randInt(0, names.length - 1)];
                    return {
                        category: type,
                        label: key,
                        correctText: pool[key],
                        questionText: "What happens with '" + key + "'?",
                    };
                },

                buildChoices: function(q) {
                    const names = q.category === 'reactivity' ? REACTIVITY_NAMES : ELECTROLYSIS_NAMES;
                    const pool = poolFor(q.category);
                    const distractors = pickOthers(names, q.label, 3).map(function(k) { return pool[k]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'reactivity') {
                        return 'A more reactive metal will always displace a less reactive one from a compound.';
                    }
                    return "Remember: 'cathode' and 'cation' both start with a positive idea — positive ions go to the cathode.";
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered electrolysis and reactivity!",
            });
        })();
    </script>
@endpush
