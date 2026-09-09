@extends('layouts.app')

@section('meta_title', 'Rock Cycle — Kids Earth Science Game')
@section('meta_blurb', 'A free earth science game for kids — learn how igneous, sedimentary and metamorphic rocks form, and the processes that shape them.')
@section('meta_words', 'rock cycle game, kids earth science game, igneous sedimentary metamorphic rock, weathering erosion')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-arrow-clockwise',
        'title' => 'Rock Cycle',
        'subtitle' => 'Pick your question types, then test your rock cycle knowledge!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'types', 'label' => 'Rock types'],
            ['id' => 'process', 'label' => 'Rock cycle processes'],
        ],
        'aboutTitle' => 'About this rock cycle game',
        'aboutText' => 'This free earth science game covers how igneous, sedimentary and metamorphic rocks form, plus the processes that shape the rock cycle — weathering, erosion, melting, cooling, and heat and pressure. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TYPES = {
                'Igneous rock': 'Forms when molten magma or lava cools and hardens, like granite or basalt.',
                'Sedimentary rock': 'Forms when layers of sediment are compressed and cemented together over time, like sandstone or limestone.',
                'Metamorphic rock': 'Forms when existing rock is changed by heat and pressure, like marble or slate.',
                'Granite': 'Forms when magma cools slowly deep underground, creating large mineral crystals — a hard igneous rock used in worktops and buildings.',
                'Basalt': 'Forms when lava cools quickly at the surface, creating small mineral crystals — a dark igneous rock common in volcanic areas.',
                'Sandstone': 'Forms when grains of sand are compressed and cemented together over millions of years — a sedimentary rock often orange or yellow.',
                'Limestone': 'Forms from the compressed remains of sea creatures and shells on the ocean floor — a sedimentary rock often used to make cement.',
                'Marble': 'Forms when limestone is changed by intense heat and pressure deep underground — a hard, crystalline metamorphic rock.',
                'Slate': 'Forms when clay-rich rock is compressed by heat and pressure, splitting easily into thin, flat sheets — a metamorphic rock used for roof tiles.',
                'Obsidian': 'Forms when lava cools so quickly it has no time to form crystals, creating smooth, glassy black igneous rock.',
            };
            const TYPE_NAMES = Object.keys(TYPES);

            const PROCESS = {
                'Weathering': 'Breaking down rocks into smaller pieces through wind, rain, ice or plant roots.',
                'Erosion': 'Moving broken-down rock pieces from one place to another, often by wind or water.',
                'Melting': 'Rock is heated so much it turns into liquid magma, deep underground.',
                'Cooling': 'Melted rock (magma or lava) loses heat and turns solid again.',
                'Heat and pressure': 'Squashes and heats existing rock without melting it, changing it into a new type of rock.',
                'Transportation': 'Carrying broken-down rock fragments, or sediment, from one place to another by wind, water, ice or gravity.',
                'Deposition': 'Sediment being dropped and settled in a new location when the wind, water or ice carrying it loses energy.',
                'Compaction': 'Layers of sediment being pressed down by the weight of material above, squeezing out water and air.',
                'Cementation': 'Minerals dissolved in groundwater bind sediment grains together to form solid sedimentary rock.',
                'Crystallisation': 'Minerals form and grow into crystals as magma or lava cools and solidifies.',
            };
            const PROCESS_NAMES = Object.keys(PROCESS);

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
                storageKey: 'rockCycleGame.settings',
                types: ['types', 'process'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'types') {
                        const rockType = TYPE_NAMES[randInt(0, TYPE_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: TYPES[rockType],
                            questionText: "How does '" + rockType + "' form?",
                        };
                    }
                    const process = PROCESS_NAMES[randInt(0, PROCESS_NAMES.length - 1)];
                    return {
                        category: type,
                        correctText: PROCESS[process],
                        questionText: "What is '" + process + "'?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'types') {
                        const rockType = TYPE_NAMES.find(function(t) { return TYPES[t] === q.correctText; });
                        const distractors = pickOthers(TYPE_NAMES, rockType, 2).map(function(t) { return TYPES[t]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const process = PROCESS_NAMES.find(function(p) { return PROCESS[p] === q.correctText; });
                    const distractors = pickOthers(PROCESS_NAMES, process, 3).map(function(p) { return PROCESS[p]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'types') {
                        return 'Think about cooled lava, squashed layers of sediment, or existing rock changed by heat and pressure.';
                    }
                    return 'Think about breaking rock down, moving the pieces, melting, cooling, or squashing and heating without melting.';
                },

                explanationFor: function(q) {
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a rock cycle superstar!",
            });
        })();
    </script>
@endpush
