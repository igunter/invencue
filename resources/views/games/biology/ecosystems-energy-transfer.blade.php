@extends('layouts.app')

@section('meta_title', 'Ecosystems & Energy Transfer — GCSE Biology Game')
@section('meta_blurb', 'A free GCSE biology game covering food chains, trophic levels and the ~10% rule for energy transfer between them.')
@section('meta_words', 'ecosystems game, energy transfer game, gcse biology game, food chain, trophic level, biomass pyramid, ecology revision')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-graph-up',
        'title' => 'Ecosystems & Energy Transfer',
        'subtitle' => 'Pick your question types, then test your ecology knowledge!',
        'typeToggles' => [
            ['id' => 'terms', 'label' => 'Key terms'],
            ['id' => 'energyTransfer', 'label' => 'Energy transfer calculations'],
        ],
        'aboutTitle' => 'About this ecosystems & energy transfer game',
        'aboutText' => 'This free GCSE biology game covers food chain and food web vocabulary, plus the classic "only around 10%" rule for how much energy passes between trophic levels. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Producer': 'An organism, usually a green plant or algae, that makes its own food using light energy.',
                'Consumer': 'An organism that gets its energy by eating other organisms.',
                'Predator': 'An animal that hunts and eats other animals.',
                'Prey': 'An animal that is hunted and eaten by a predator.',
                'Trophic level': 'A stage in a food chain, such as producer, primary consumer or secondary consumer.',
                'Biomass': 'The mass of living material in an organism or ecosystem.',
                'Decomposer': 'An organism that breaks down dead material and waste, releasing nutrients.',
                'Food web': 'A diagram showing how several connected food chains in a habitat relate to each other.',
                'Pyramid of biomass': 'A diagram showing how biomass decreases at each trophic level going up the chain.',
                'Ecosystem': 'All the living organisms and non-living conditions in an area, and how they interact.',
                'Habitat': 'The place where an organism lives within its ecosystem.',
                'Population': 'All the organisms of one species living in a particular area at the same time.',
            };
            const TERM_NAMES = Object.keys(TERMS);
            const ENERGY_VALUES = [1000, 2000, 4000, 5000, 8000, 10000, 20000];

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
                storageKey: 'ecosystemsEnergyTransferGame.settings',
                types: ['terms', 'energyTransfer'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
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
                    const base = ENERGY_VALUES[randInt(0, ENERGY_VALUES.length - 1)];
                    const correct = base * 0.1;
                    return {
                        category: type,
                        base: base,
                        correctText: correct + ' kJ',
                        questionText: 'A producer has ' + base + ' kJ of stored energy. Using the standard ~10% transfer rule, roughly how much energy passes to the next trophic level?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'terms') {
                        const term = TERM_NAMES.find(function(t) { return TERMS[t] === q.correctText; });
                        const distractors = pickOthers(TERM_NAMES, term, 3).map(function(t) { return TERMS[t]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const base = q.base;
                    const distractors = [base + ' kJ', Math.round(base * 0.01) + ' kJ', Math.round(base * 0.9) + ' kJ'];
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'terms') {
                        return 'Think about where this fits in a food chain — is it about who eats what, or about the mass and energy itself?';
                    }
                    return 'On average only around 10% of the energy at one trophic level passes to the next — most is lost as heat, movement, and in waste and faeces.';
                },

                explanationFor: function(q) {
                    if (q.category === 'energyTransfer') return '10% of ' + q.base + ' kJ = ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You've mastered ecosystems and energy transfer!",
            });
        })();
    </script>
@endpush
