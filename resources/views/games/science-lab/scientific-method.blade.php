@extends('layouts.app')

@section('meta_title', 'Scientific Method — Science Game')
@section('meta_blurb', 'A free science game for school kids — put the steps of a science investigation into the right order.')
@section('meta_words', 'scientific method game, science game, working scientifically, hypothesis, investigation steps in order')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-list-ol',
        'title' => 'Scientific Method',
        'subtitle' => 'Pick your question types, then order the steps!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'whatComesNext', 'label' => 'What comes next?'],
            ['id' => 'defineStep', 'label' => 'What does this step mean?'],
        ],
        'aboutTitle' => 'About this scientific method game',
        'aboutText' => 'This free science game helps school kids learn the steps of a science investigation, in order — asking a question, writing a hypothesis, planning and carrying out a fair test, recording results, and drawing a conclusion. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const STEPS = [
                'Ask a question',
                'Write a hypothesis (a prediction)',
                'Plan a fair test',
                'Carry out the investigation',
                'Record the results',
                'Draw a conclusion',
                'Evaluate the method',
            ];

            const DEFINITIONS = {
                'Ask a question': 'Decide what you want to find out.',
                'Write a hypothesis (a prediction)': 'Predict what you think will happen, and why.',
                'Plan a fair test': 'Decide what to change, what to measure, and what to keep the same.',
                'Carry out the investigation': 'Follow your plan and collect data.',
                'Record the results': 'Write down your measurements, often in a table.',
                'Draw a conclusion': 'Explain what your results show and whether they support your hypothesis.',
                'Evaluate the method': 'Think about how the investigation could be improved.',
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
                storageKey: 'scientificMethodGame.settings',
                types: ['whatComesNext', 'defineStep'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 25,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'whatComesNext') {
                        const index = randInt(0, STEPS.length - 2);
                        return {
                            category: type,
                            index: index,
                            correctText: STEPS[index + 1],
                            questionText: 'After "' + STEPS[index] + '", what is the next step in a science investigation?',
                        };
                    }

                    const step = STEPS[randInt(0, STEPS.length - 1)];
                    return {
                        category: type,
                        label: step,
                        correctText: DEFINITIONS[step],
                        questionText: 'What does this step mean: "' + step + '"?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'whatComesNext') {
                        const distractors = pickOthers(STEPS, q.correctText, 3);
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(STEPS, q.label, 3).map(function(s) { return DEFINITIONS[s]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'whatComesNext') {
                        return 'A science investigation follows: question, hypothesis, plan, carry out, record, conclude, evaluate.';
                    }
                    return 'Think about what actually happens during this step of an investigation.';
                },

                explanationFor: function(q) {
                    if (q.category === 'defineStep') return q.label + ': ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a scientific method superstar!",
            });
        })();
    </script>
@endpush
