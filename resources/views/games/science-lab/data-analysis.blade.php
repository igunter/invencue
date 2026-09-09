@extends('layouts.app')

@section('meta_title', 'Data Analysis — GCSE Science Lab Game')
@section('meta_blurb', 'A free GCSE science game — calculate the mean of a set of results, plus key data-analysis terms like anomaly, range, trend, precision and accuracy.')
@section('meta_words', 'data analysis game, gcse science game, mean calculation, anomaly, range, trend, precision, accuracy, working scientifically')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-table',
        'title' => 'Data Analysis',
        'subtitle' => 'Pick your question types, then crunch some results!',
        'typeToggles' => [
            ['id' => 'mean', 'label' => 'Calculate the mean'],
            ['id' => 'terms', 'label' => 'Key terms'],
        ],
        'aboutTitle' => 'About this data analysis game',
        'aboutText' => 'This free GCSE science game practises calculating the mean from a small set of results, plus the vocabulary needed to describe and evaluate data — anomaly, range, trend, precision and accuracy. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const VALUE_POOL = [4, 6, 8, 10, 12, 14, 16, 18, 20, 22, 24, 26];

            const TERMS = {
                'Anomaly': 'A result that does not fit the pattern of the other data — often excluded when calculating an average.',
                'Range': 'The difference between the highest and lowest value in a set of data.',
                'Trend': 'The general pattern shown by a set of data, such as increasing, decreasing or staying constant.',
                'Precision': 'How close repeated measurements are to each other.',
                'Accuracy': 'How close a measurement is to the true value.',
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

            function round1(n) {
                return Math.round(n * 10) / 10;
            }

            function buildMeanQuestion() {
                for (let attempt = 0; attempt < 10; attempt++) {
                    const values = [];
                    for (let i = 0; i < 4; i++) {
                        values.push(VALUE_POOL[randInt(0, VALUE_POOL.length - 1)]);
                    }
                    const total = values.reduce(function(a, b) { return a + b; }, 0);
                    const mean = round1(total / 4);
                    if (values.indexOf(mean) === -1) {
                        return { values: values, total: total, mean: mean };
                    }
                }
                // fallback if every attempt collided (extremely unlikely)
                return { values: [4, 6, 8, 10], total: 28, mean: 7 };
            }

            window.ScienceQuiz.run({
                storageKey: 'dataAnalysisGame.settings',
                types: ['mean', 'terms'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 25,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'terms') {
                        const term = TERM_NAMES[randInt(0, TERM_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: TERMS[term],
                            questionText: "What does '" + term + "' mean?",
                        };
                    }
                    const q = buildMeanQuestion();
                    return {
                        category: type,
                        values: q.values,
                        total: q.total,
                        correctText: String(q.mean),
                        questionText: 'A student records these results: ' + q.values.join(', ') + '. What is the mean?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'terms') {
                        const term = TERM_NAMES.find(function(t) { return TERMS[t] === q.correctText; });
                        const distractors = pickOthers(TERM_NAMES, term, 3).map(function(t) { return TERMS[t]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const wrongSum = String(q.total);
                    const wrongDivBy3 = String(round1(q.total / 3));
                    const wrongOneValue = String(q.values[randInt(0, q.values.length - 1)]);
                    return shuffle([q.correctText, wrongSum, wrongDivBy3, wrongOneValue]);
                },

                hintFor: function(q) {
                    if (q.category === 'mean') {
                        return 'Add up all the values, then divide by how many values there are.';
                    }
                    return 'Think about whether this is a description of one odd result, a spread of values, an overall direction, or how repeatable/correct the data is.';
                },

                explanationFor: function(q) {
                    if (q.category === 'mean') return q.values.join(' + ') + ' = ' + q.total + ', ÷ 4 = ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You've mastered data analysis!",
            });
        })();
    </script>
@endpush
