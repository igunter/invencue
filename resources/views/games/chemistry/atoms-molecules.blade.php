@extends('layouts.app')

@section('meta_title', 'Atoms & Molecules — Kids Chemistry Game')
@section('meta_blurb', 'A free chemistry game for kids — learn what atoms, molecules, elements, compounds and mixtures are, and sort real substances into each.')
@section('meta_words', 'atoms and molecules game, kids chemistry game, elements compounds mixtures, learn chemistry basics')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-diagram-2',
        'title' => 'Atoms & Molecules',
        'subtitle' => 'Pick your question types, then test your chemistry knowledge!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'terms', 'label' => 'Key terms'],
            ['id' => 'examples', 'label' => 'Element, compound or mixture?'],
        ],
        'aboutTitle' => 'About this atoms & molecules game',
        'aboutText' => 'This free chemistry game covers what atoms, molecules, elements, compounds and mixtures are, plus sorting real substances like water, salt water and iron into the right category. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Atom': 'The smallest part of an element that can exist; made of protons, neutrons and electrons.',
                'Molecule': 'Two or more atoms joined together.',
                'Element': 'A substance made of only one type of atom.',
                'Compound': 'A substance made of two or more different elements chemically joined together.',
                'Mixture': 'Two or more substances mixed together but not chemically joined, so they can be separated again.',
                'Proton': 'A positively charged particle found in the nucleus of an atom.',
                'Neutron': 'A particle with no electrical charge found in the nucleus of an atom.',
                'Electron': 'A tiny, negatively charged particle that orbits the nucleus of an atom.',
                'Nucleus (atom)': 'The small, dense centre of an atom, containing protons and neutrons.',
                'Ion': 'An atom or molecule that has gained or lost electrons, giving it an electrical charge.',
            };
            const TERM_NAMES = Object.keys(TERMS);

            const EXAMPLES = {
                'Oxygen gas (O₂)': 'Element',
                'Water (H₂O)': 'Compound',
                'Salt water': 'Mixture',
                'Carbon dioxide (CO₂)': 'Compound',
                'Iron (Fe)': 'Element',
                'Air': 'Mixture',
                'Sodium chloride (table salt)': 'Compound',
                'Copper (Cu)': 'Element',
                'Sand and water stirred together': 'Mixture',
                'Glucose (C₆H₁₂O₆)': 'Compound',
            };
            const EXAMPLE_NAMES = Object.keys(EXAMPLES);
            const CATEGORY_LIST = ['Element', 'Compound', 'Mixture'];

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
                storageKey: 'atomsMoleculesGame.settings',
                types: ['terms', 'examples'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'terms') {
                        const term = TERM_NAMES[randInt(0, TERM_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: TERMS[term],
                            questionText: "What is a '" + term + "'?",
                        };
                    }
                    const example = EXAMPLE_NAMES[randInt(0, EXAMPLE_NAMES.length - 1)];
                    return {
                        category: type,
                        correctText: EXAMPLES[example],
                        questionText: 'Is ' + example + ' an element, a compound, or a mixture?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'terms') {
                        const term = TERM_NAMES.find(function(t) { return TERMS[t] === q.correctText; });
                        const distractors = pickOthers(TERM_NAMES, term, 3).map(function(t) { return TERMS[t]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    return shuffle(CATEGORY_LIST.slice());
                },

                hintFor: function(q) {
                    if (q.category === 'terms') {
                        return 'Think about single particles, joined particles, one type of atom, different atoms joined, or things that can be separated.';
                    }
                    return 'One type of atom = element. Different atoms chemically joined = compound. Substances just mixed together = mixture.';
                },

                explanationFor: function(q) {
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're an atoms and molecules superstar!",
            });
        })();
    </script>
@endpush
