@extends('layouts.app')

@section('meta_title', 'Approaches in Psychology — GCSE Psychology Game')
@section('meta_blurb', 'A free GCSE psychology game covering the behaviourist, cognitive, biological, humanistic and psychodynamic approaches.')
@section('meta_words', 'approaches in psychology game, gcse psychology game, behaviourist cognitive biological humanistic psychodynamic')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-diagram-2',
        'title' => 'Approaches in Psychology',
        'subtitle' => 'Read the clue, then name the approach or concept!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Approaches'],
        ],
        'aboutTitle' => 'About this approaches in psychology game',
        'aboutText' => 'This free GCSE psychology game covers the main approaches used to explain behaviour, including the behaviourist, cognitive, biological, psychodynamic and humanistic approaches, plus key debates like nature vs nurture and free will vs determinism.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const APPROACHES = {
                'Behaviourist approach': 'Focuses on observable behaviour and how it is learned through conditioning.',
                'Cognitive approach': 'Focuses on internal mental processes like memory, thinking and perception.',
                'Biological approach': 'Explains behaviour in terms of genetics, brain structure and neurochemistry.',
                'Psychodynamic approach': 'Focuses on unconscious processes and childhood experiences, founded by Freud.',
                'Humanistic approach': 'Focuses on free will, personal growth and reaching your full potential.',
                'Social learning theory': 'Explains behaviour as learned through observing and imitating others.',
                'Nature approach': 'Explains behaviour mainly in terms of inherited, genetic factors.',
                'Nurture approach': 'Explains behaviour mainly in terms of environment and experience.',
                'Reductionism': 'Explaining complex behaviour by breaking it down into simpler parts, such as brain chemicals.',
                'Holism': 'Studying behaviour by looking at the whole person or situation, not just individual parts.',
                'Determinism': 'The idea that behaviour is caused by factors outside of our control, like genes or environment.',
                'Free will': 'The idea that people can choose and control their own behaviour.',
            };
            const NAMES = Object.keys(APPROACHES);

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
                storageKey: 'approachesInPsychologyGame.settings',
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
                        questionText: APPROACHES[name],
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(NAMES, q.label, 3);
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about what this approach focuses on — the mind, behaviour, biology, or something else.';
                },

                explanationFor: function(q) {
                    return q.correctText + ': ' + APPROACHES[q.correctText];
                },

                masteryMessage: "Amazing! You understand the key approaches in psychology!",
            });
        })();
    </script>
@endpush
