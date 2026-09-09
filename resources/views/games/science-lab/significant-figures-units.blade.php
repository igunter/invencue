@extends('layouts.app')

@section('meta_title', 'Significant Figures & Units — GCSE Science Lab Game')
@section('meta_blurb', 'A free GCSE science game — round numbers to a given number of significant figures, and match quantities to their correct SI unit.')
@section('meta_words', 'significant figures game, units game, gcse science game, rounding, SI units, newtons, joules, working scientifically')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-hash',
        'title' => 'Significant Figures & Units',
        'subtitle' => 'Pick your question types, then round and match!',
        'typeToggles' => [
            ['id' => 'sigfig', 'label' => 'Significant figures'],
            ['id' => 'units', 'label' => 'SI units'],
        ],
        'aboutTitle' => 'About this significant figures & units game',
        'aboutText' => 'This free GCSE science game practises rounding numbers to a given number of significant figures, and matching common quantities to the correct SI unit — from newtons and joules to amps and hertz. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const SIGFIG_QUESTIONS = [
                { value: '23456', sf: 2, correct: '23000', wrong: ['24000', '23500', '20000'] },
                { value: '23456', sf: 3, correct: '23500', wrong: ['23000', '23460', '24000'] },
                { value: '0.04567', sf: 2, correct: '0.046', wrong: ['0.045', '0.0457', '0.05'] },
                { value: '0.04567', sf: 3, correct: '0.0457', wrong: ['0.046', '0.045', '0.0456'] },
                { value: '789.4', sf: 2, correct: '790', wrong: ['780', '789', '800'] },
                { value: '789.4', sf: 3, correct: '789', wrong: ['790', '788', '800'] },
                { value: '6.789', sf: 2, correct: '6.8', wrong: ['6.7', '6.79', '7.0'] },
                { value: '6.789', sf: 3, correct: '6.79', wrong: ['6.8', '6.78', '6.80'] },
                { value: '0.1234', sf: 2, correct: '0.12', wrong: ['0.13', '0.123', '0.1'] },
                { value: '0.1234', sf: 3, correct: '0.123', wrong: ['0.12', '0.124', '0.1234'] },
            ];

            const UNITS = {
                'Force': 'Newtons (N)',
                'Energy': 'Joules (J)',
                'Mass': 'Kilograms (kg)',
                'Current': 'Amps (A)',
                'Potential difference (voltage)': 'Volts (V)',
                'Time': 'Seconds (s)',
                'Temperature': 'Kelvin (K) or degrees Celsius (°C)',
                'Frequency': 'Hertz (Hz)',
                'Pressure': 'Pascals (Pa)',
                'Electric charge': 'Coulombs (C)',
            };
            const UNIT_NAMES = Object.keys(UNITS);

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
                storageKey: 'sigFigUnitsGame.settings',
                types: ['sigfig', 'units'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'sigfig') {
                        const q = SIGFIG_QUESTIONS[randInt(0, SIGFIG_QUESTIONS.length - 1)];
                        return {
                            category: type,
                            correctText: q.correct,
                            wrong: q.wrong,
                            questionText: 'Round ' + q.value + ' to ' + q.sf + ' significant figures.',
                        };
                    }
                    const quantity = UNIT_NAMES[randInt(0, UNIT_NAMES.length - 1)];
                    return {
                        category: type,
                        label: quantity,
                        correctText: UNITS[quantity],
                        questionText: 'What is the SI unit for ' + quantity.toLowerCase() + '?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'sigfig') {
                        return shuffle([q.correctText].concat(q.wrong));
                    }
                    const distractors = pickOthers(UNIT_NAMES, q.label, 3).map(function(u) { return UNITS[u]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'sigfig') {
                        return 'Count significant figures from the first non-zero digit. Round the last one up if the next digit is 5 or more.';
                    }
                    return 'Think about what kind of quantity this is — a push/pull, a transfer of energy, an amount of matter, or a flow of charge.';
                },

                explanationFor: function(q) {
                    if (q.category === 'units') return q.label + ': ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You've mastered significant figures and units!",
            });
        })();
    </script>
@endpush
