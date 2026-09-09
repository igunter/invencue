@extends('layouts.app')

@section('meta_title', 'Coastal Landforms — GCSE Geography Game')
@section('meta_blurb', 'A free GCSE geography game covering coastal erosion and deposition landforms — headlands, spits, stacks and more.')
@section('meta_words', 'coastal landforms game, gcse geography game, coastal erosion deposition, headland spit stack arch, gcse geography revision')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-droplet-half',
        'title' => 'Coastal Landforms',
        'subtitle' => 'Pick your question types, then test your coastal geography knowledge!',
        'typeToggles' => [
            ['id' => 'erosion', 'label' => 'Erosion landforms'],
            ['id' => 'deposition', 'label' => 'Deposition landforms'],
        ],
        'aboutTitle' => 'About this coastal landforms game',
        'aboutText' => 'This free GCSE geography game covers the landforms created by coastal erosion — headlands, caves, arches and stacks — and by coastal deposition — spits, bars and beaches. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const EROSION = {
                'Headland': 'A section of coastline that sticks out into the sea, formed where softer rock either side has eroded faster than a band of harder rock.',
                'Bay': 'A curved, sheltered stretch of coastline formed where softer rock has been eroded faster than the harder rock on either side.',
                'Cave': 'A hollow formed in a cliff or headland where waves force their way into a crack, widening it through erosion.',
                'Arch': 'A landform created when a cave erodes all the way through a headland, leaving a bridge of rock over open water.',
                'Stack': 'A tall column of rock left standing after the roof of an arch collapses, separated from the headland.',
                'Stump': 'A short, worn-down remnant of a stack left after it has been eroded and collapsed by wave action.',
                'Wave-cut platform': 'A flat, rocky area left at the base of a cliff after erosion has caused the cliff to retreat inland.',
                'Wave-cut notch': 'A dent cut into a cliff at the high-tide line where wave erosion attacks the base of the cliff hardest.',
                'Hydraulic action': 'An erosion process where the force of waves compresses air into cracks in rock, weakening it.',
                'Abrasion (corrasion)': 'An erosion process where waves fling sand and pebbles against a cliff, wearing it away.',
            };
            const EROSION_NAMES = Object.keys(EROSION);

            const DEPOSITION = {
                'Spit': 'A narrow ridge of sand or shingle that extends out from the coast and often curves at the end due to changing wind and wave direction.',
                'Bar': 'A ridge of sand or shingle that joins two headlands together, sometimes trapping a lagoon behind it.',
                'Beach': 'An area of sand or shingle built up by constructive waves along the coastline.',
                'Sand dune': 'A mound of sand built up by wind above the beach, often stabilised by marram grass.',
                'Tombolo': 'A ridge of sand or shingle that connects the mainland to an island.',
                'Longshore drift': 'The process that moves sand and shingle along a coastline in a zig-zag path caused by waves approaching at an angle.',
                'Constructive wave': 'A low-energy wave with a strong swash and weak backwash, which deposits more material than it removes.',
                'Swash': 'The forward movement of water and sediment up a beach as a wave breaks.',
                'Backwash': 'The backward movement of water and sediment down a beach, pulled by gravity.',
                'Salt marsh': 'A muddy, vegetated wetland area that can form in the sheltered water behind a spit or bar.',
            };
            const DEPOSITION_NAMES = Object.keys(DEPOSITION);

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

            function bankFor(type) {
                return type === 'erosion' ? EROSION : DEPOSITION;
            }

            function namesFor(type) {
                return type === 'erosion' ? EROSION_NAMES : DEPOSITION_NAMES;
            }

            window.ScienceQuiz.run({
                storageKey: 'coastalLandformsGame.settings',
                types: ['erosion', 'deposition'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const names = namesFor(type);
                    const bank = bankFor(type);
                    const term = names[randInt(0, names.length - 1)];
                    return {
                        category: type,
                        label: term,
                        correctText: bank[term],
                        questionText: "What is a '" + term + "'?",
                    };
                },

                buildChoices: function(q) {
                    const bank = bankFor(q.category);
                    const names = namesFor(q.category);
                    const distractors = pickOthers(names, q.label, 3).map(function(t) { return bank[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'erosion') {
                        return 'Think about how waves wear away rock over time, and what shape is left behind.';
                    }
                    return 'Think about how waves build up sand and shingle in sheltered or slower-moving water.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered coastal landforms!",
            });
        })();
    </script>
@endpush
