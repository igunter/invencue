@extends('layouts.app')

@section('meta_title', 'Life Cycles — Kids Biology Game')
@section('meta_blurb', 'A free biology game for young kids — put the stages of a frog, butterfly or plant life cycle in the right order.')
@section('meta_words', 'life cycles game, kids biology game, frog life cycle, butterfly life cycle, plant life cycle, tadpole, caterpillar')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-arrow-repeat',
        'title' => 'Life Cycles',
        'subtitle' => 'Pick your question types, then order those life cycles!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'stages', 'label' => "What's next?"],
            ['id' => 'facts', 'label' => 'Life cycle facts'],
        ],
        'aboutTitle' => 'About this life cycles game',
        'aboutText' => 'This free biology game helps young kids learn the stages of a frog, butterfly and plant life cycle, from egg or seed all the way to adult. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const SEQUENCES = {
                'frog': ['Egg', 'Tadpole', 'Froglet', 'Adult frog'],
                'butterfly': ['Egg', 'Caterpillar', 'Chrysalis', 'Adult butterfly'],
                'plant': ['Seed', 'Seedling', 'Young plant', 'Adult plant with flowers'],
            };
            const CREATURES = Object.keys(SEQUENCES);
            const ALL_STAGES = Array.from(new Set([].concat.apply([], Object.values(SEQUENCES))));

            const FACTS = {
                'frog': 'Starts as an egg in water, becomes a tadpole with a tail, then a froglet with legs, before becoming an adult frog.',
                'butterfly': 'Starts as an egg, hatches into a caterpillar, forms a chrysalis, then emerges as an adult butterfly.',
                'plant': 'Starts as a seed, grows into a seedling, then a young plant, before becoming an adult plant that can flower and make new seeds.',
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

            function pickOthers(pool, exclude, count) {
                return shuffle(pool.filter(function(x) { return x !== exclude; })).slice(0, count);
            }

            window.ScienceQuiz.run({
                storageKey: 'lifeCyclesGame.settings',
                types: ['stages', 'facts'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'stages') {
                        const creature = CREATURES[randInt(0, CREATURES.length - 1)];
                        const seq = SEQUENCES[creature];
                        const index = randInt(0, seq.length - 2);
                        return {
                            category: type,
                            correctText: seq[index + 1],
                            questionText: "In a " + creature + "'s life cycle, what comes after '" + seq[index] + "'?",
                        };
                    }
                    const creature = CREATURES[randInt(0, CREATURES.length - 1)];
                    return {
                        category: type,
                        label: creature,
                        correctText: FACTS[creature],
                        questionText: "What happens in a " + creature + "'s life cycle?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'stages') {
                        const distractors = pickOthers(ALL_STAGES, q.correctText, 3);
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(CREATURES, q.label, 3).map(function(c) { return FACTS[c]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'stages') {
                        return 'Think about what an egg turns into first, and what the very last, grown-up stage looks like.';
                    }
                    return 'Think about where each one starts — in water, as a tiny caterpillar, or buried in the soil.';
                },

                explanationFor: function(q) {
                    if (q.category === 'facts') return q.label + ': ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a life cycles superstar!",
            });
        })();
    </script>
@endpush
