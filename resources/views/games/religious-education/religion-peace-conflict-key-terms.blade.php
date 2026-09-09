@extends('layouts.app')

@section('meta_title', 'Religion, Peace & Conflict: Key Terms — GCSE Religious Studies Game')
@section('meta_blurb', 'A free GCSE Religious Studies game covering peace and conflict vocabulary — Just War Theory, pacifism, ahimsa and more, defined neutrally.')
@section('meta_words', 'religion peace and conflict key terms game, gcse religious studies game, just war theory pacifism ahimsa quiz, aqa edexcel re')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-flag',
        'title' => 'Religion, Peace & Conflict: Key Terms',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Peace & conflict key terms'],
        ],
        'aboutTitle' => 'About this religion, peace & conflict: key terms game',
        'aboutText' => 'This free GCSE Religious Studies game covers peace and conflict vocabulary used in GCSE exam specifications — including Just War Theory, pacifism, ahimsa and forgiveness — each defined neutrally, as established theories and terms, without reference to any real-world event or conflict.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Just War Theory': 'A set of conditions, developed within Christian tradition, that a war must meet for it to be considered morally justified.',
                'Pacifism': 'The belief that violence and war are never justified, and that conflicts should always be resolved peacefully.',
                'Holy War': 'A war that is believed to be sanctioned by God or fought for a religious cause.',
                'Ahimsa': 'A principle of non-violence and doing no harm to any living being, important in Hinduism, Buddhism, Jainism and Sikhism.',
                'Conscientious Objector': 'A person who refuses to take part in fighting on moral or religious grounds.',
                'Forgiveness': 'Letting go of anger or blame towards someone who has caused harm.',
                'Reconciliation': 'The process of restoring a friendly relationship after conflict or disagreement.',
                'Justice': 'Fairness, and ensuring people are treated as they deserve to be treated.',
                'Peace': 'A state free from war, violence or disturbance.',
                'Turning the Other Cheek': 'A Christian teaching, from the Sermon on the Mount, of responding to harm without retaliation.',
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
                storageKey: 'religionPeaceConflictKeyTermsGame.settings',
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
                    return 'Think about whether this term is about justifying conflict, avoiding it, or repairing relationships afterwards.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered religion, peace and conflict key terms!",
            });
        })();
    </script>
@endpush
