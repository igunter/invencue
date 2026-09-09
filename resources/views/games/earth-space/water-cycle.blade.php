@extends('layouts.app')

@section('meta_title', 'The Water Cycle — Kids Earth Science Game')
@section('meta_blurb', 'A free earth science game for kids — put the stages of the water cycle in order and learn what evaporation, condensation, precipitation and collection mean.')
@section('meta_words', 'water cycle game, kids earth science game, evaporation condensation precipitation collection')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-arrow-repeat',
        'title' => 'The Water Cycle',
        'subtitle' => 'Pick your question types, then test your water cycle knowledge!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'stages', 'label' => "What's next?"],
            ['id' => 'terms', 'label' => 'Stage definitions'],
        ],
        'aboutTitle' => 'About this water cycle game',
        'aboutText' => 'This free earth science game covers the four stages of the water cycle — evaporation, condensation, precipitation and collection — in order, and what each stage actually means. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const STAGES = [
                'Evaporation', 'Transpiration', 'Sublimation', 'Condensation', 'Precipitation',
                'Interception', 'Infiltration', 'Percolation', 'Runoff', 'Collection',
            ];

            const TERMS = {
                'Evaporation': 'Water heats up and turns into water vapour (a gas), rising into the air.',
                'Transpiration': 'Water evaporates from tiny pores in plant leaves, adding more water vapour to the air.',
                'Sublimation': 'Ice or snow turns directly into water vapour without melting into liquid first.',
                'Condensation': 'Water vapour cools and turns back into tiny liquid droplets, forming clouds.',
                'Precipitation': 'Water falls from clouds as rain, snow, sleet or hail.',
                'Interception': 'Falling rain or snow is caught by leaves, branches or buildings before it reaches the ground.',
                'Infiltration': 'Water soaks down into the soil at the ground surface.',
                'Percolation': 'Water already in the soil seeps further down through rock layers to become groundwater.',
                'Runoff': 'Water flows over the land surface into streams, rivers and eventually the sea.',
                'Collection': 'Water gathers in rivers, lakes and oceans, ready to evaporate again.',
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
                storageKey: 'waterCycleGame.settings',
                types: ['stages', 'terms'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'stages') {
                        const index = randInt(0, STAGES.length - 1);
                        const nextIndex = (index + 1) % STAGES.length;
                        return {
                            category: type,
                            correctText: STAGES[nextIndex],
                            questionText: "In the water cycle, what stage comes after '" + STAGES[index] + "'?",
                        };
                    }
                    const term = STAGES[randInt(0, STAGES.length - 1)];
                    return {
                        category: type,
                        correctText: TERMS[term],
                        questionText: "What happens during '" + term + "'?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'stages') {
                        const distractors = pickOthers(STAGES, q.correctText, 3);
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const term = STAGES.find(function(t) { return TERMS[t] === q.correctText; });
                    const distractors = pickOthers(STAGES, term, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    return 'The cycle goes: evaporation, condensation, precipitation, collection — then it starts all over again.';
                },

                explanationFor: function(q) {
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a water cycle superstar!",
            });
        })();
    </script>
@endpush
