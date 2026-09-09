@extends('layouts.app')

@section('meta_title', 'Moles & Calculations — GCSE Chemistry Game')
@section('meta_blurb', 'A free GCSE chemistry numeracy game — calculate moles from mass and relative formula mass (Mr), plus key mole vocabulary.')
@section('meta_words', 'moles game, gcse chemistry game, relative formula mass, Mr calculation, Avogadro constant, mole calculations revision')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-calculator',
        'title' => 'Moles & Calculations',
        'subtitle' => 'Pick your question types, then crunch some numbers!',
        'typeToggles' => [
            ['id' => 'moles', 'label' => 'Mole calculations'],
            ['id' => 'terms', 'label' => 'Key terms'],
        ],
        'aboutTitle' => 'About this moles & calculations game',
        'aboutText' => 'This free GCSE chemistry game practises the core mole calculation — moles = mass ÷ Mr — across six common compounds, plus the vocabulary behind it (mole, Mr, Ar, Avogadro\'s constant, concentration). Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const COMPOUNDS = {
                'water (H₂O)': 18,
                'carbon dioxide (CO₂)': 44,
                'sodium chloride (NaCl)': 58.5,
                'calcium carbonate (CaCO₃)': 100,
                'magnesium oxide (MgO)': 40,
                'sodium hydroxide (NaOH)': 40,
                'methane (CH₄)': 16,
                'ammonia (NH₃)': 17,
                'sulfuric acid (H₂SO₄)': 98,
                'calcium oxide (CaO)': 56,
            };
            const COMPOUND_NAMES = Object.keys(COMPOUNDS);
            const MOLE_VALUES = [0.5, 2, 3, 4, 5]; // 1 excluded: Mr/mass (the "inverted formula" distractor) equals the correct answer when moles = 1

            const TERMS = {
                'Mole': 'The unit chemists use to count particles — one mole of anything contains 6.02 × 10²³ particles.',
                'Relative formula mass (Mr)': 'The sum of the relative atomic masses of all the atoms in a formula.',
                'Relative atomic mass (Ar)': "The average mass of an atom of an element, compared to 1/12th the mass of a carbon-12 atom.",
                "Avogadro's constant": 'The number of particles in one mole of a substance: 6.02 × 10²³.',
                'Concentration': 'The amount of solute dissolved in a given volume of solution, often measured in mol/dm³.',
                'Empirical formula': 'The simplest whole-number ratio of atoms of each element in a compound.',
                'Molar mass': 'The mass of one mole of a substance, measured in grams per mole (g/mol) — numerically equal to its Mr.',
                'Limiting reactant': 'The reactant that is used up first in a reaction, stopping the reaction and limiting how much product can form.',
                'Yield': 'The amount of product actually obtained from a reaction, often compared with the maximum possible amount as a percentage.',
                'Solute': 'The substance that is dissolved in a solvent to make a solution.',
            };
            const TERM_NAMES = Object.keys(TERMS);

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

            function fmt(n) {
                return (Math.round(n * 100) / 100).toString();
            }

            window.ScienceQuiz.run({
                storageKey: 'molesCalculationsGame.settings',
                types: ['moles', 'terms'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 25,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'terms') {
                        const term = TERM_NAMES[randInt(0, TERM_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: TERMS[term],
                            questionText: "What does '" + term + "' mean?",
                        };
                    }
                    const compound = COMPOUND_NAMES[randInt(0, COMPOUND_NAMES.length - 1)];
                    const mr = COMPOUNDS[compound];
                    const moles = MOLE_VALUES[randInt(0, MOLE_VALUES.length - 1)];
                    const mass = mr * moles;
                    return {
                        category: type,
                        mr: mr,
                        mass: mass,
                        correctText: fmt(moles) + ' mol',
                        questionText: 'How many moles are in ' + fmt(mass) + ' g of ' + compound + '? (Mr = ' + mr + ')',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'terms') {
                        const term = TERM_NAMES.find(function(t) { return TERMS[t] === q.correctText; });
                        const distractors = pickOthers(TERM_NAMES, term, 3).map(function(t) { return TERMS[t]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const wrongMultiply = fmt(q.mass * q.mr) + ' mol';
                    const wrongInvert = fmt(q.mr / q.mass) + ' mol';
                    const wrongDecimal = fmt((q.mass / q.mr) * 10) + ' mol';
                    return shuffle([q.correctText, wrongMultiply, wrongInvert, wrongDecimal]);
                },

                hintFor: function(q) {
                    if (q.category === 'terms') {
                        return 'Think about whether this is a counting unit, a mass value, or a number used for counting particles.';
                    }
                    return 'Moles = mass ÷ Mr. Divide the mass given by the Mr shown in brackets.';
                },

                explanationFor: function(q) {
                    if (q.category === 'moles') return fmt(q.mass) + ' ÷ ' + q.mr + ' = ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You've mastered mole calculations!",
            });
        })();
    </script>
@endpush
