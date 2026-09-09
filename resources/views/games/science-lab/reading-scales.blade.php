@extends('layouts.app')

@section('meta_title', 'Reading Scales — Science Game')
@section('meta_blurb', 'A free science game for school kids — read values from rulers, thermometers, measuring cylinders and force meters.')
@section('meta_words', 'reading scales game, science game, measuring cylinder reading, thermometer reading, newton meter reading, working scientifically')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-sliders',
        'title' => 'Reading Scales',
        'subtitle' => 'Pick your question types, then read the scale!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'ruler', 'label' => 'Ruler'],
            ['id' => 'thermometer', 'label' => 'Thermometer'],
            ['id' => 'cylinder', 'label' => 'Measuring cylinder'],
            ['id' => 'forceMeter', 'label' => 'Force meter'],
        ],
        'aboutTitle' => 'About this reading scales game',
        'aboutText' => 'This free science game helps school kids practise reading values from four common pieces of lab equipment — rulers, thermometers, measuring cylinders and force meters — including scales that count up in twos, fives or tens. Choose which equipment to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const SCALES = {
                ruler: { unit: 'cm', min: 0, max: 30, steps: [1, 2] },
                thermometer: { unit: '°C', min: -20, max: 60, steps: [2, 5] },
                cylinder: { unit: 'ml', min: 0, max: 200, steps: [5, 10] },
                forceMeter: { unit: 'N', min: 0, max: 20, steps: [1, 2] },
            };

            const LABELS = {
                ruler: 'ruler',
                thermometer: 'thermometer',
                cylinder: 'measuring cylinder',
                forceMeter: 'force meter',
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
                storageKey: 'readingScalesGame.settings',
                types: ['ruler', 'thermometer', 'cylinder', 'forceMeter'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-6',

                buildQuestion: function(type) {
                    const scale = SCALES[type];
                    const step = scale.steps[randInt(0, scale.steps.length - 1)];
                    const numSteps = Math.floor((scale.max - scale.min) / step);
                    const value = scale.min + step * randInt(1, numSteps - 1);
                    return {
                        category: type,
                        scale: scale,
                        step: step,
                        correctText: value + ' ' + scale.unit,
                        questionText: 'The pointer on the ' + LABELS[type] + ' is at ' + value + ' ' + scale.unit + '. What is this reading?',
                    };
                },

                buildChoices: function(q) {
                    const scale = q.scale;
                    const step = q.step;
                    const correctValue = parseFloat(q.correctText);
                    const numSteps = Math.floor((scale.max - scale.min) / step);
                    const pool = [];
                    for (let i = 0; i <= numSteps; i++) {
                        pool.push(scale.min + step * i);
                    }
                    const distractors = pickOthers(pool, correctValue, 3).map(function(v) { return v + ' ' + scale.unit; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    return 'Count along the scale from the last labelled number, using steps of ' + q.step + ' ' + q.scale.unit + ' each time.';
                },

                explanationFor: function(q) {
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a reading scales superstar!",
            });
        })();
    </script>
@endpush
