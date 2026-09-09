@extends('layouts.app')

@section('meta_title', 'Resource Management — GCSE Geography Game')
@section('meta_blurb', 'A free GCSE geography game covering water, energy and food resource management key terms and concepts.')
@section('meta_words', 'resource management game, gcse geography game, water energy food security, sustainable resource use, gcse geography revision')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-recycle',
        'title' => 'Resource Management',
        'subtitle' => 'Read the clue, then work out the answer!',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Resource management'],
        ],
        'aboutTitle' => 'About this resource management game',
        'aboutText' => 'This free GCSE geography game covers key vocabulary around managing water, energy and food resources, including supply, demand, security and sustainability.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Water security': 'Having reliable access to a sufficient quantity and quality of water for health, livelihoods and production.',
                'Water stress': 'When the demand for water in an area is close to or exceeds the available supply.',
                'Water surplus': 'When the supply of water in an area is greater than the demand for it.',
                'Water deficit': 'When the demand for water in an area is greater than the available supply.',
                'Energy security': 'Having an uninterrupted and affordable supply of energy to meet a country\'s needs.',
                'Energy mix': 'The combination of different energy sources, such as fossil fuels and renewables, that a country uses.',
                'Renewable energy': 'Energy from sources that are naturally replenished, such as wind, solar or hydroelectric power.',
                'Non-renewable energy': 'Energy from sources that will eventually run out, such as coal, oil and gas.',
                'Food security': 'Having reliable access to enough safe, affordable and nutritious food.',
                'Food insecurity': 'Not having reliable access to enough safe, affordable and nutritious food.',
                'Food miles': 'The distance food travels from where it is produced to where it is eaten.',
                'Sustainable resource management': 'Using resources in a way that meets people\'s needs today without harming the ability of future generations to meet their own needs.',
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
                storageKey: 'resourceManagementGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const term = TERM_NAMES[randInt(0, TERM_NAMES.length - 1)];
                    return {
                        category: type,
                        label: term,
                        correctText: TERMS[term],
                        questionText: "What does '" + term + "' mean?",
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about whether this term is about water, energy or food, and whether it describes having enough, too much, or too little.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered resource management!",
            });
        })();
    </script>
@endpush
