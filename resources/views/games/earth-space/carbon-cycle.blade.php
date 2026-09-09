@extends('layouts.app')

@section('meta_title', 'The Carbon Cycle — GCSE Earth Science Game')
@section('meta_blurb', 'A free GCSE earth science game covering the processes and vocabulary of the carbon cycle — photosynthesis, respiration, combustion, decomposition and fossilisation.')
@section('meta_words', 'carbon cycle game, gcse earth science game, greenhouse gas, carbon sink, fossil fuel, deforestation revision')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-arrow-repeat',
        'title' => 'The Carbon Cycle',
        'subtitle' => 'Pick your question types, then test your carbon cycle knowledge!',
        'typeToggles' => [
            ['id' => 'processes', 'label' => 'Carbon cycle processes'],
            ['id' => 'terms', 'label' => 'Key terms'],
        ],
        'aboutTitle' => 'About this carbon cycle game',
        'aboutText' => 'This free GCSE earth science game covers the processes that move carbon through the environment — photosynthesis, respiration, combustion, decomposition and fossilisation — plus vocabulary like carbon sink, greenhouse gas and deforestation. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const PROCESSES = {
                'Photosynthesis': 'Plants absorb carbon dioxide from the air and convert it into glucose, storing carbon in the process.',
                'Respiration': 'Living organisms release carbon dioxide back into the atmosphere as they break down glucose for energy.',
                'Combustion': 'Burning fossil fuels or wood releases stored carbon back into the atmosphere as carbon dioxide.',
                'Decomposition': 'Decomposers break down dead organisms and waste, releasing carbon dioxide back into the atmosphere.',
                'Fossilisation': 'Over millions of years, buried dead organic matter can be compressed into fossil fuels, locking carbon away underground.',
                'Volcanic activity': 'Volcanoes release carbon dioxide that has been stored deep within the Earth back into the atmosphere.',
                'Ocean absorption': 'The oceans absorb carbon dioxide from the atmosphere, dissolving it in seawater.',
                'Weathering of rocks': 'Rainwater slowly reacts with rocks, locking carbon away in new carbonate minerals over long timescales.',
                'Carbon capture and storage': 'A technology that captures carbon dioxide from power stations and factories and stores it underground.',
                'Extraction of fossil fuels': 'Removing coal, oil and gas from underground so they can be burned, moving carbon from long-term storage towards the atmosphere.',
            };
            const PROCESS_NAMES = Object.keys(PROCESSES);

            const TERMS = {
                'Carbon sink': 'A natural store that absorbs more carbon than it releases, such as oceans or forests.',
                'Greenhouse gas': 'A gas, like carbon dioxide or methane, that traps heat in the atmosphere.',
                'Fossil fuel': 'A fuel such as coal, oil or natural gas, formed from the remains of ancient organisms.',
                'Deforestation': 'The clearing of forests, which reduces the amount of carbon dioxide absorbed by trees.',
                'Carbon cycle': 'The continuous movement of carbon between the atmosphere, living things, oceans and rocks.',
                'Biomass': 'The total mass of living organisms in an area, which contains carbon stored in their bodies.',
                'Ocean acidification': 'The oceans becoming more acidic as they absorb extra carbon dioxide from the atmosphere.',
                'Reforestation': 'Planting new trees on cleared land to absorb more carbon dioxide from the atmosphere.',
                'Carbon neutral': 'An activity or process that adds no net carbon dioxide to the atmosphere.',
                'Atmosphere': 'The layer of gases surrounding the Earth, where carbon dioxide is temporarily stored.',
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

            window.ScienceQuiz.run({
                storageKey: 'carbonCycleGame.settings',
                types: ['processes', 'terms'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'processes') {
                        const process = PROCESS_NAMES[randInt(0, PROCESS_NAMES.length - 1)];
                        return {
                            category: type,
                            label: process,
                            correctText: PROCESSES[process],
                            questionText: "What happens during '" + process + "' in the carbon cycle?",
                        };
                    }
                    const term = TERM_NAMES[randInt(0, TERM_NAMES.length - 1)];
                    return {
                        category: type,
                        correctText: TERMS[term],
                        questionText: "What does '" + term + "' mean?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'processes') {
                        const distractors = pickOthers(PROCESS_NAMES, q.label, 3).map(function(p) { return PROCESSES[p]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const term = TERM_NAMES.find(function(t) { return TERMS[t] === q.correctText; });
                    const distractors = pickOthers(TERM_NAMES, term, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'processes') {
                        return 'Think about whether carbon is being taken OUT of the atmosphere, or put BACK INTO it, by this process.';
                    }
                    return 'Think about whether this is a place carbon is stored, a gas, a fuel, or a human activity that changes the balance.';
                },

                explanationFor: function(q) {
                    if (q.category === 'processes') return q.label + ': ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You've mastered the carbon cycle!",
            });
        })();
    </script>
@endpush
