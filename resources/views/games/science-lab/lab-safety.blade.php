@extends('layouts.app')

@section('meta_title', 'Lab Safety — Kids Science Game')
@section('meta_blurb', 'A free science game for young kids — spot safe and unsafe things to do in a science lab, and learn the rules that keep everyone safe.')
@section('meta_words', 'lab safety game, kids science game, science lab rules, safety goggles, working scientifically for kids')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-shield-check',
        'title' => 'Lab Safety',
        'subtitle' => 'Pick your question types, then spot what\'s safe!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'behaviour', 'label' => 'Safe or unsafe?'],
            ['id' => 'rules', 'label' => 'Why do we do it?'],
        ],
        'aboutTitle' => 'About this lab safety game',
        'aboutText' => 'This free science game helps young kids spot safe and unsafe things to do in a science lab, and learn why we wear safety goggles, tie back hair, and follow other lab rules. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const BEHAVIOUR = {
                'Wearing safety goggles when mixing chemicals': 'Safe',
                'Tying back long hair before using a Bunsen burner': 'Safe',
                'Washing your hands after an experiment': 'Safe',
                'Running around in the science lab': 'Unsafe',
                'Tasting a substance to see what it is': 'Unsafe',
                'Pointing a test tube at a friend': 'Unsafe',
                'Leaving a hot Bunsen burner unattended': 'Unsafe',
            };
            const BEHAVIOUR_NAMES = Object.keys(BEHAVIOUR);

            const RULES = {
                'Safety goggles': 'Protect your eyes from splashes and chemicals.',
                'A lab coat or apron': 'Protects your clothes and skin from spills.',
                'Telling an adult about spills or breakages': 'Lets them clean it up safely straight away.',
                'Never eating or drinking in a lab': 'Stops chemicals or germs from getting into your food or drink.',
            };
            const RULE_NAMES = Object.keys(RULES);

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
                storageKey: 'labSafetyGame.settings',
                types: ['behaviour', 'rules'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'behaviour') {
                        const action = BEHAVIOUR_NAMES[randInt(0, BEHAVIOUR_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: BEHAVIOUR[action],
                            questionText: action + ' — is this safe or unsafe?',
                        };
                    }
                    const rule = RULE_NAMES[randInt(0, RULE_NAMES.length - 1)];
                    return {
                        category: type,
                        label: rule,
                        correctText: RULES[rule],
                        questionText: "Why do we follow this rule: '" + rule + "'?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'behaviour') {
                        return shuffle(['Safe', 'Unsafe']);
                    }
                    const distractors = pickOthers(RULE_NAMES, q.label, 3).map(function(r) { return RULES[r]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'behaviour') {
                        return 'Would a careful scientist do this, or could it hurt someone?';
                    }
                    return 'Think about protecting eyes, protecting clothes and skin, cleaning up quickly, or keeping food away from chemicals.';
                },

                explanationFor: function(q) {
                    if (q.category === 'rules') return q.label + ': ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a lab safety superstar!",
            });
        })();
    </script>
@endpush
