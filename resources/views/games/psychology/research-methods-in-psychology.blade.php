@extends('layouts.app')

@section('meta_title', 'Research Methods in Psychology — GCSE Psychology Game')
@section('meta_blurb', 'A free GCSE psychology game covering research methods — experiments, surveys, case studies and validity.')
@section('meta_words', 'research methods game, gcse psychology game, experiments surveys case studies, validity reliability correlation')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-search',
        'title' => 'Research Methods in Psychology',
        'subtitle' => 'Read the clue, then name the term!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Research methods'],
        ],
        'aboutTitle' => 'About this research methods in psychology game',
        'aboutText' => 'This free GCSE psychology game covers research methods used by psychologists, including experiments, surveys, case studies, correlation vs causation, and the basics of validity and reliability.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Experiment': 'A research method where a researcher manipulates one variable to see its effect on another.',
                'Independent variable': 'The variable that a researcher changes or manipulates in an experiment.',
                'Dependent variable': 'The variable that is measured in an experiment, which may change because of the independent variable.',
                'Survey': 'A research method that collects data by asking people questions, often using questionnaires.',
                'Case study': 'An in-depth investigation of a single individual, group or event.',
                'Correlation': 'A relationship between two variables, showing they change together, without proving one causes the other.',
                'Causation': 'When a change in one variable directly causes a change in another variable.',
                'Validity': 'Whether a study actually measures what it claims to measure.',
                'Reliability': 'Whether a study or measurement produces consistent results if repeated.',
                'Sample': 'The group of participants selected to take part in a study.',
                'Control group': 'A group in an experiment that does not receive the treatment, used for comparison.',
                'Extraneous variable': 'An unwanted variable that could affect the results of an experiment if not controlled.',
            };
            const NAMES = Object.keys(TERMS);

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
                storageKey: 'researchMethodsInPsychologyGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const name = NAMES[randInt(0, NAMES.length - 1)];
                    return {
                        category: type,
                        label: name,
                        correctText: name,
                        questionText: TERMS[name],
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(NAMES, q.label, 3);
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about how the method is used to collect or interpret data.';
                },

                explanationFor: function(q) {
                    return q.correctText + ': ' + TERMS[q.correctText];
                },

                masteryMessage: "Amazing! You've mastered research methods in psychology!",
            });
        })();
    </script>
@endpush
