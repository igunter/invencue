@extends('layouts.app')

@section('meta_title', 'Rates of Reaction — GCSE Chemistry Game')
@section('meta_blurb', 'A free GCSE chemistry game covering collision theory and the factors that speed up a reaction — temperature, concentration, surface area, catalysts and pressure.')
@section('meta_words', 'rates of reaction game, collision theory, gcse chemistry game, catalyst, activation energy, reaction rate revision')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-stopwatch',
        'title' => 'Rates of Reaction',
        'subtitle' => 'Pick your question types, then test your reaction rate knowledge!',
        'typeToggles' => [
            ['id' => 'terms', 'label' => 'Key terms'],
            ['id' => 'factors', 'label' => 'Factors affecting rate'],
        ],
        'aboutTitle' => 'About this rates of reaction game',
        'aboutText' => 'This free GCSE chemistry game covers collision theory and the five factors that change how fast a reaction happens — temperature, concentration, surface area, catalysts and pressure. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Collision theory': 'The idea that for a reaction to happen, particles must collide with enough energy to react.',
                'Activation energy': 'The minimum energy particles need when they collide for a reaction to happen.',
                'Catalyst': 'A substance that speeds up a reaction without being used up itself.',
                'Rate of reaction': 'How quickly reactants are used up, or products are formed, in a reaction.',
            };
            const TERM_NAMES = Object.keys(TERMS);

            const FACTORS = {
                'Increasing temperature': 'Particles move faster and collide more often and with more energy, increasing the rate.',
                'Increasing concentration': 'There are more particles in the same volume, so collisions happen more often, increasing the rate.',
                'Decreasing particle size (more surface area)': 'More particles are exposed and available to collide, increasing the rate.',
                'Adding a catalyst': 'It lowers the activation energy needed, increasing the rate without being used up.',
                'Increasing pressure of a gas': 'Particles are pushed closer together, so collisions happen more often, increasing the rate.',
            };
            const FACTOR_NAMES = Object.keys(FACTORS);

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
                storageKey: 'ratesOfReactionGame.settings',
                types: ['terms', 'factors'],
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
                    const factor = FACTOR_NAMES[randInt(0, FACTOR_NAMES.length - 1)];
                    return {
                        category: type,
                        label: factor,
                        correctText: FACTORS[factor],
                        questionText: factor + ' — what effect does this have on the rate of reaction, and why?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'terms') {
                        const term = TERM_NAMES.find(function(t) { return TERMS[t] === q.correctText; });
                        const distractors = pickOthers(TERM_NAMES, term, 3).map(function(t) { return TERMS[t]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(FACTOR_NAMES, q.label, 3).map(function(f) { return FACTORS[f]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'terms') {
                        return 'Think about whether this describes speed itself, the energy needed to react, or something that helps without being used up.';
                    }
                    return 'Every one of these factors increases the rate — the question is really about HOW it increases the number or energy of collisions.';
                },

                explanationFor: function(q) {
                    if (q.category === 'factors') return q.label + ': ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You've mastered rates of reaction!",
            });
        })();
    </script>
@endpush
