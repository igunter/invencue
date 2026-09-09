@extends('layouts.app')

@section('meta_title', 'Religion & Ethics: Key Terms — GCSE Religious Studies Game')
@section('meta_blurb', 'A free GCSE Religious Studies game covering key ethical terms — sanctity of life, Just War Theory, pacifism and more, defined neutrally.')
@section('meta_words', 'religion and ethics key terms game, gcse religious studies game, sanctity of life just war pacifism quiz, aqa edexcel re')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-list-check',
        'title' => 'Religion & Ethics: Key Terms',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Ethics key terms'],
        ],
        'aboutTitle' => 'About this religion & ethics: key terms game',
        'aboutText' => 'This free GCSE Religious Studies game covers key ethics vocabulary used in GCSE exam specifications — including sanctity of life, Just War Theory, pacifism and situation ethics — with each term defined neutrally, as it would appear in a textbook glossary, without taking a side.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Sanctity of Life': 'The belief that all life is sacred and holy because it was created by God, and should therefore be protected.',
                'Quality of Life': 'The idea that the value of a life should be judged by its quality, such as a person\'s wellbeing and ability to function.',
                'Just War Theory': 'A set of conditions, developed within Christian tradition, that a war must meet to be considered morally justified.',
                'Pacifism': 'The belief that violence and war are never justified, and that conflicts should be resolved peacefully.',
                'Holy War': 'A war that is believed to be sanctioned by God or fought for a religious cause.',
                'Euthanasia': 'The act of deliberately ending a person\'s life to relieve suffering, usually at their request.',
                'Situation Ethics': 'An ethical approach that judges each action based on what would bring about the most loving result in that particular situation.',
                'Natural Law': 'An ethical theory holding that there are unchanging moral laws built into nature, discoverable through reason.',
                'Conscience': 'A person\'s inner sense of right and wrong that guides their moral decisions.',
                'Utilitarianism': 'An ethical theory that judges an action as right if it produces the greatest good, or happiness, for the greatest number of people.',
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
                storageKey: 'religionEthicsKeyTermsGame.settings',
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
                    return 'Think about which part of ethics this term relates to — the value of life, conflict, or how moral decisions are made.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered religion and ethics key terms!",
            });
        })();
    </script>
@endpush
