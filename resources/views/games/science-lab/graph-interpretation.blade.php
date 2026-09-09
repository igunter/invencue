@extends('layouts.app')

@section('meta_title', 'Graph Interpretation — GCSE Science Lab Game')
@section('meta_blurb', 'A free GCSE science game — calculate the gradient of a straight-line graph, and interpret what gradients, intercepts and lines of best fit show.')
@section('meta_words', 'graph interpretation game, gcse science game, gradient calculation, line of best fit, intercept, working scientifically')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-graph-up',
        'title' => 'Graph Interpretation',
        'subtitle' => 'Pick your question types, then read those graphs!',
        'typeToggles' => [
            ['id' => 'gradient', 'label' => 'Calculate the gradient'],
            ['id' => 'reading', 'label' => 'Reading graph features'],
        ],
        'aboutTitle' => 'About this graph interpretation game',
        'aboutText' => 'This free GCSE science game practises calculating the gradient of a straight-line graph from two points, plus what gradients, intercepts and lines of best fit actually show about the data. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const M_VALUES = [2, 3, 4, 5, 0.5];
            // Kept clear of M_VALUES on purpose: if a gradient and an x2 ever
            // matched (e.g. m=2, x2=2), the "just read off x2" distractor
            // would coincide with the correct gradient.
            const X2_VALUES = [6, 8, 10];

            const READING = {
                'A positive gradient': 'Shows that as the x-value increases, the y-value also increases.',
                'A negative gradient': 'Shows that as the x-value increases, the y-value decreases.',
                'A steeper gradient': 'Shows a faster rate of change between the two variables.',
                'An intercept (where a line crosses the y-axis)': 'Shows the starting value of y when x is zero.',
                'A line of best fit': 'A line drawn through a set of data points to show the overall trend, ignoring anomalies.',
                'A directly proportional relationship': 'A straight line through the origin, showing that y increases at the same rate as x.',
                'Extrapolation': 'Using a graph to estimate a value beyond the range of the data actually collected.',
                'Interpolation': 'Using a graph to estimate a value that falls between two data points already collected.',
                'A positive correlation': 'Shows that as one variable increases, the other variable also tends to increase.',
                'An anomalous point on a graph': 'A point that does not fit the general trend of the other data points.',
            };
            const READING_NAMES = Object.keys(READING);

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

            function fmt(n) {
                return (Math.round(n * 100) / 100).toString();
            }

            window.ScienceQuiz.run({
                storageKey: 'graphInterpretationGame.settings',
                types: ['gradient', 'reading'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'reading') {
                        const feature = READING_NAMES[randInt(0, READING_NAMES.length - 1)];
                        return {
                            category: type,
                            label: feature,
                            correctText: READING[feature],
                            questionText: "What does '" + feature + "' show on a graph?",
                        };
                    }
                    const m = M_VALUES[randInt(0, M_VALUES.length - 1)];
                    const x2 = X2_VALUES[randInt(0, X2_VALUES.length - 1)];
                    const y2 = m * x2;
                    return {
                        category: type,
                        m: m,
                        x2: x2,
                        y2: y2,
                        correctText: fmt(m),
                        questionText: 'A straight line passes through (0, 0) and (' + x2 + ', ' + y2 + '). What is the gradient of the line?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'reading') {
                        const distractors = pickOthers(READING_NAMES, q.label, 3).map(function(f) { return READING[f]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const wrongY2 = fmt(q.y2);
                    const wrongX2 = fmt(q.x2);
                    const wrongInvert = fmt(q.x2 / q.y2);
                    return shuffle([q.correctText, wrongY2, wrongX2, wrongInvert]);
                },

                hintFor: function(q) {
                    if (q.category === 'reading') {
                        return 'Gradient is about steepness and direction. Intercept is about the starting value. A line of best fit is about the overall trend.';
                    }
                    return 'Gradient = change in y ÷ change in x. Since the line passes through (0,0), that is just y2 ÷ x2.';
                },

                explanationFor: function(q) {
                    if (q.category === 'gradient') return q.y2 + ' ÷ ' + q.x2 + ' = ' + q.correctText;
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered graph interpretation!",
            });
        })();
    </script>
@endpush
