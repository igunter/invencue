@extends('layouts.app')

@section('meta_title', 'Evaluating Experiments — GCSE Science Lab Game')
@section('meta_blurb', 'A free GCSE science game covering sources of error in an experiment (systematic vs random) and how to improve reliability and precision.')
@section('meta_words', 'evaluating experiments game, gcse science game, systematic error, random error, zero error, reliability, precision')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-exclamation-triangle-fill',
        'title' => 'Evaluating Experiments',
        'subtitle' => 'Pick your question types, then spot the problem!',
        'typeToggles' => [
            ['id' => 'errors', 'label' => 'Sources of error'],
            ['id' => 'improvements', 'label' => 'Improving an experiment'],
        ],
        'aboutTitle' => 'About this evaluating experiments game',
        'aboutText' => 'This free GCSE science game covers spotting systematic and random errors in an experiment, and how to improve reliability, precision and accuracy. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const ERRORS = {
                'Using a ruler with worn-down end markings': 'A systematic error — every reading will be off by roughly the same amount in the same direction.',
                'Viewing a scale at an angle (parallax error)': 'A random error — this can make readings too high or too low unpredictably.',
                'Not resetting a balance to zero before weighing': 'A systematic (zero) error — it shifts every reading by a fixed amount.',
                'Human reaction time varying when using a stopwatch': 'A random error — reaction time varies slightly each time it is measured.',
                'Reading a measuring cylinder from above instead of at eye level': 'A random error — parallax can make readings vary unpredictably higher or lower.',
                'A thermometer that always reads 2°C too high': 'A systematic error — every reading is shifted by the same fixed amount.',
                'Slight draughts affecting a balance during weighing': 'A random error — small unpredictable changes affect each reading differently.',
                'A newton meter with a stretched spring that was never recalibrated': 'A systematic error — every force reading will be off by a similar amount.',
                'Background noise making it hard to judge exactly when a reaction finishes': 'A random error — it makes readings inconsistent in an unpredictable way.',
                'A measuring cylinder that was manufactured with a slightly inaccurate scale': 'A systematic error — every volume reading will be off in the same direction.',
            };
            const ERROR_NAMES = Object.keys(ERRORS);

            const IMPROVEMENTS = {
                'To reduce the effect of random errors': 'Repeat the experiment several times and calculate a mean.',
                'To check a result is reliable': 'Repeat the experiment and see if similar results are obtained each time.',
                'To remove a systematic (zero) error': 'Recalibrate the equipment — for example, resetting a balance to zero before use.',
                'To make an experiment more precise': 'Use more precise measuring equipment, such as a device with a smaller scale division.',
                'To reduce parallax error when reading a scale': 'Position your eye level with the scale when taking the reading.',
                'To improve the accuracy of a result': 'Compare it with a known true value and reduce sources of error in the method.',
                'To make an experiment more reproducible': 'Make sure the method is described clearly enough for someone else to follow it exactly.',
                'To reduce human error when timing an event': 'Use a data logger or light gate instead of a stopwatch and reaction time.',
                'To spot an anomalous result': 'Compare each result with the others and look for one that does not fit the pattern.',
                'To control extra variables in an experiment': 'Identify what else could affect the result and keep those factors the same.',
            };
            const IMPROVEMENT_NAMES = Object.keys(IMPROVEMENTS);

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
                storageKey: 'evaluatingExperimentsGame.settings',
                types: ['errors', 'improvements'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const pool = type === 'errors' ? ERRORS : IMPROVEMENTS;
                    const names = type === 'errors' ? ERROR_NAMES : IMPROVEMENT_NAMES;
                    const key = names[randInt(0, names.length - 1)];
                    return {
                        category: type,
                        label: key,
                        correctText: pool[key],
                        questionText: type === 'errors' ? (key + ' — what kind of error is this?') : (key + ' — what should you do?'),
                    };
                },

                buildChoices: function(q) {
                    const pool = q.category === 'errors' ? ERRORS : IMPROVEMENTS;
                    const names = q.category === 'errors' ? ERROR_NAMES : IMPROVEMENT_NAMES;
                    const distractors = pickOthers(names, q.label, 3).map(function(k) { return pool[k]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'errors') {
                        return 'A systematic error shifts every reading the same way. A random error makes readings vary unpredictably, both higher and lower.';
                    }
                    return 'Repeating readings helps with random error. Recalibrating equipment helps with systematic (zero) error.';
                },

                explanationFor: function(q) {
                    return q.correctText;
                },

                masteryMessage: "Amazing! You've mastered evaluating experiments!",
            });
        })();
    </script>
@endpush
