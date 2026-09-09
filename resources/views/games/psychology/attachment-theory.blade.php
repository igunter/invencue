@extends('layouts.app')

@section('meta_title', 'Attachment Theory — GCSE Psychology Game')
@section('meta_blurb', 'A free GCSE psychology game covering attachment theory — Bowlby, Ainsworth and attachment styles.')
@section('meta_words', 'attachment theory game, bowlby ainsworth, strange situation, secure insecure attachment, gcse psychology game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-heart-fill',
        'title' => 'Attachment Theory',
        'subtitle' => 'Read the clue, then name the term!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Attachment theory'],
        ],
        'aboutTitle' => 'About this attachment theory game',
        'aboutText' => 'This free GCSE psychology game covers attachment theory, including Bowlby and Ainsworth\'s research, the Strange Situation, and attachment styles such as secure, insecure-avoidant and insecure-resistant attachment.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Attachment': 'A close emotional bond between an infant and a caregiver.',
                'John Bowlby': 'The psychologist who developed attachment theory, suggesting infants have an innate need to bond with a caregiver.',
                'Mary Ainsworth': 'The psychologist who developed the Strange Situation to study attachment styles.',
                'Strange Situation': "A study where researchers observed how infants react to separation and reunion with their caregiver.",
                'Secure attachment': 'An attachment style where an infant is confident using a caregiver as a safe base and is easily comforted on reunion.',
                'Insecure-avoidant attachment': 'An attachment style where an infant shows little distress on separation and avoids the caregiver on reunion.',
                'Insecure-resistant attachment': 'An attachment style where an infant is very distressed on separation and hard to comfort on reunion.',
                'Stranger anxiety': 'The distress an infant shows around unfamiliar people, common from around 6-8 months.',
                'Separation anxiety': 'Distress shown by an infant when separated from their main caregiver.',
                'Secure base': 'The idea that a caregiver provides a safe point from which an infant can explore the world.',
                'Critical period': 'A limited time window in early life when attachment is thought to form most easily.',
                'Internal working model': 'A mental template of relationships, formed from early attachment experiences, that shapes future relationships.',
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
                storageKey: 'attachmentTheoryGame.settings',
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
                    return "Think about the bond between an infant and their caregiver, and how it's studied.";
                },

                explanationFor: function(q) {
                    return q.correctText + ': ' + TERMS[q.correctText];
                },

                masteryMessage: "Amazing! You've mastered attachment theory!",
            });
        })();
    </script>
@endpush
