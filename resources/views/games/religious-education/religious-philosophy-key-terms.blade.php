@extends('layouts.app')

@section('meta_title', 'Religious Philosophy & Key Terms — GCSE Religious Studies Game')
@section('meta_blurb', 'A free GCSE Religious Studies game covering philosophy vocabulary — omnipotent, omniscient, agnosticism, atheism and more.')
@section('meta_words', 'religious philosophy key terms game, gcse religious studies game, omnipotent omniscient agnosticism atheism quiz, aqa edexcel re')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-lightbulb',
        'title' => 'Religious Philosophy & Key Terms',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Philosophy key terms'],
        ],
        'aboutTitle' => 'About this religious philosophy & key terms game',
        'aboutText' => 'This free GCSE Religious Studies game covers core religious philosophy vocabulary used in GCSE exam specifications — including omnipotent, omniscient, omnibenevolent, agnosticism and atheism — each defined as it would appear in a textbook glossary.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Omnipotent': 'All-powerful; a description many religions apply to God.',
                'Omniscient': 'All-knowing; a description many religions apply to God.',
                'Omnibenevolent': 'All-loving and perfectly good; a description many religions apply to God.',
                'Transcendent': 'Existing beyond and outside of the physical universe.',
                'Immanent': 'Present and active within the world and in people\'s everyday lives.',
                'Theism': 'Belief in the existence of one or more gods.',
                'Monotheism': 'Belief in the existence of only one God.',
                'Polytheism': 'Belief in the existence of more than one god or deity.',
                'Agnosticism': 'The view that it is not possible to know for certain whether God exists or not.',
                'Atheism': 'The lack of belief in the existence of God or gods.',
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

            window.ScienceQuiz.run({
                storageKey: 'religiousPhilosophyKeyTermsGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const term = TERM_NAMES[randInt(0, TERM_NAMES.length - 1)];
                    return {
                        category: type,
                        label: term,
                        correctText: TERMS[term],
                        questionText: "What is the textbook definition of '" + term + "'?",
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about whether this term describes a property of God, or a view about whether God exists.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered religious philosophy key terms!",
            });
        })();
    </script>
@endpush
