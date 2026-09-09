@extends('layouts.app')

@section('meta_title', 'Electricity Calculations — GCSE Physics Game')
@section('meta_blurb', "A free GCSE physics numeracy game — Ohm's law (V = IR) and electrical power (P = VI) calculations.")
@section('meta_words', "electricity calculations game, gcse physics game, ohm's law, V=IR, power calculation, P=VI, circuits revision")

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-hash',
        'title' => 'Electricity Calculations',
        'subtitle' => 'Pick your question types, then crunch some circuit numbers!',
        'typeToggles' => [
            ['id' => 'ohms', 'label' => "Ohm's law (V = IR)"],
            ['id' => 'power', 'label' => 'Power (P = VI)'],
        ],
        'aboutTitle' => 'About this electricity calculations game',
        'aboutText' => "This free GCSE physics game practises the two core circuit equations — Ohm's law (V = IR) and electrical power (P = VI) — with randomised numbers each time. Choose which question types to include, set your question count and time limit, then see how many you can get right.",
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            // 1 and 2 are excluded from these pools: with a simple "multiply
            // two numbers" formula, a value of 1 makes the "divide instead of
            // multiply" distractor equal the correct answer, and a pair of 2s
            // makes "add instead of multiply" equal it too.
            const CURRENTS = [3, 4, 5, 10];
            const RESISTANCES = [3, 4, 5, 6, 10, 20];
            const VOLTAGES = [3, 4, 5, 10, 20, 30];
            const POWER_CURRENTS = [3, 4, 5, 6, 10];

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

            function distractorsFor(a, b, correct, unit) {
                const wrongAdd = (a + b) + unit;
                const wrongDivide = Math.round((a / b) * 100) / 100 + unit;
                const wrongDouble = (a * b * 2) + unit;
                return shuffle([correct + unit, wrongAdd, wrongDivide, wrongDouble]);
            }

            window.ScienceQuiz.run({
                storageKey: 'electricityCalculationsGame.settings',
                types: ['ohms', 'power'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'ohms') {
                        const current = CURRENTS[randInt(0, CURRENTS.length - 1)];
                        const resistance = RESISTANCES[randInt(0, RESISTANCES.length - 1)];
                        const voltage = current * resistance;
                        return {
                            category: type,
                            a: current,
                            b: resistance,
                            correctText: voltage + ' V',
                            questionText: 'A current of ' + current + ' A flows through a ' + resistance + ' Ω resistor. What is the potential difference across it? (V = I × R)',
                        };
                    }
                    const voltage = VOLTAGES[randInt(0, VOLTAGES.length - 1)];
                    const current = POWER_CURRENTS[randInt(0, POWER_CURRENTS.length - 1)];
                    const power = voltage * current;
                    return {
                        category: type,
                        a: voltage,
                        b: current,
                        correctText: power + ' W',
                        questionText: 'A device runs at ' + voltage + ' V and draws ' + current + ' A of current. What is its power? (P = V × I)',
                    };
                },

                buildChoices: function(q) {
                    const unit = q.category === 'ohms' ? ' V' : ' W';
                    const correct = q.a * q.b;
                    return distractorsFor(q.a, q.b, correct, unit);
                },

                hintFor: function(q) {
                    if (q.category === 'ohms') {
                        return 'V = I × R. Multiply the current (in amps) by the resistance (in ohms).';
                    }
                    return 'P = V × I. Multiply the voltage (in volts) by the current (in amps).';
                },

                explanationFor: function(q) {
                    return q.a + ' × ' + q.b + ' = ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered electricity calculations!",
            });
        })();
    </script>
@endpush
