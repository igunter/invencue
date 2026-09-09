@extends('layouts.app')

@section('meta_title', 'Balancing Equations — GCSE Chemistry Game')
@section('meta_blurb', 'A free GCSE chemistry game — pick the correctly balanced symbol equation for classic reactions, plus key balancing concepts.')
@section('meta_words', 'balancing equations game, gcse chemistry game, chemical equations, conservation of mass, coefficients, symbol equations')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-braces',
        'title' => 'Balancing Equations',
        'subtitle' => 'Pick your question types, then balance those equations!',
        'typeToggles' => [
            ['id' => 'balancing', 'label' => 'Balance the equation'],
            ['id' => 'concepts', 'label' => 'Key concepts'],
        ],
        'aboutTitle' => 'About this balancing equations game',
        'aboutText' => 'This free GCSE chemistry game tests whether you can spot the correctly balanced symbol equation for classic reactions, plus the ideas behind why equations must balance — the law of conservation of mass, coefficients, reactants and products. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const REACTIONS = {
                'Hydrogen + oxygen → water': {
                    correct: '2H₂ + O₂ → 2H₂O',
                    wrong: ['H₂ + O₂ → H₂O', 'H₂ + O₂ → 2H₂O', '2H₂ + O₂ → H₂O'],
                },
                'Methane burning in oxygen': {
                    correct: 'CH₄ + 2O₂ → CO₂ + 2H₂O',
                    wrong: ['CH₄ + O₂ → CO₂ + H₂O', 'CH₄ + 2O₂ → CO₂ + H₂O', '2CH₄ + 2O₂ → CO₂ + 2H₂O'],
                },
                'Magnesium + oxygen → magnesium oxide': {
                    correct: '2Mg + O₂ → 2MgO',
                    wrong: ['Mg + O₂ → MgO', 'Mg + O₂ → 2MgO', '2Mg + O₂ → MgO'],
                },
                'Sodium + chlorine → sodium chloride': {
                    correct: '2Na + Cl₂ → 2NaCl',
                    wrong: ['Na + Cl₂ → NaCl', 'Na + Cl₂ → 2NaCl', '2Na + Cl₂ → NaCl'],
                },
                'Iron + sulfur → iron sulfide': {
                    correct: 'Fe + S → FeS',
                    wrong: ['2Fe + S → FeS', 'Fe + 2S → FeS', '2Fe + S → 2FeS'],
                },
            };
            const REACTION_NAMES = Object.keys(REACTIONS);

            const CONCEPTS = {
                'Law of conservation of mass': 'In a closed system, the total mass of reactants equals the total mass of products — atoms are never created or destroyed.',
                'Coefficient': 'The number written in front of a formula in an equation, showing how many units of that substance react or form.',
                'Balanced equation': 'An equation where the number of atoms of each element is the same on both sides.',
                'Reactant': 'A substance that is used up in a chemical reaction.',
                'Product': 'A substance that is made in a chemical reaction.',
            };
            const CONCEPT_NAMES = Object.keys(CONCEPTS);

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
                storageKey: 'balancingEquationsGame.settings',
                types: ['balancing', 'concepts'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 25,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'balancing') {
                        const reaction = REACTION_NAMES[randInt(0, REACTION_NAMES.length - 1)];
                        return {
                            category: type,
                            label: reaction,
                            correctText: REACTIONS[reaction].correct,
                            questionText: 'Which equation correctly represents: ' + reaction + '?',
                        };
                    }
                    const concept = CONCEPT_NAMES[randInt(0, CONCEPT_NAMES.length - 1)];
                    return {
                        category: type,
                        correctText: CONCEPTS[concept],
                        questionText: "What does '" + concept + "' mean?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'balancing') {
                        return shuffle([q.correctText].concat(REACTIONS[q.label].wrong));
                    }
                    const concept = CONCEPT_NAMES.find(function(c) { return CONCEPTS[c] === q.correctText; });
                    const distractors = pickOthers(CONCEPT_NAMES, concept, 3).map(function(c) { return CONCEPTS[c]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'balancing') {
                        return 'Count the atoms of each element on both sides of the arrow — they must match exactly.';
                    }
                    return 'Think about whether this is about the numbers in front of formulae, or about mass staying the same overall.';
                },

                explanationFor: function(q) {
                    if (q.category === 'balancing') return q.label + ': ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You've mastered balancing equations!",
            });
        })();
    </script>
@endpush
