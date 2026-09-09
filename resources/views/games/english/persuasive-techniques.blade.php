@extends('layouts.app')

@section('meta_title', 'Persuasive Techniques — GCSE English Game')
@section('meta_blurb', 'A free GCSE English game covering persuasive writing techniques — rule of three, rhetorical questions, emotive language and more.')
@section('meta_words', 'persuasive techniques game, gcse english language game, rhetorical devices, emotive language, rule of three, persuasive writing')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-megaphone',
        'title' => 'Persuasive Techniques',
        'subtitle' => 'Read the technique, then pick what it means!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Persuasive techniques'],
        ],
        'aboutTitle' => 'About this persuasive techniques game',
        'aboutText' => 'This free GCSE English game covers the techniques writers and speakers use to persuade an audience — the rule of three, rhetorical questions, emotive language, repetition, statistics, anecdotes and more.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Rule of three': 'Listing three points together to make an argument more memorable and persuasive.',
                'Rhetorical question': 'A question asked to make a point rather than to get an answer.',
                'Emotive language': 'Words chosen to create a strong emotional reaction in the reader.',
                'Repetition': "Repeating a word or phrase to emphasise a point and make it stick in the reader's mind.",
                'Statistics': 'Using numbers or data to make an argument seem more convincing and factual.',
                'Anecdote': 'A short personal story used to make an argument feel more relatable.',
                'Direct address': 'Speaking straight to the reader, using words like "you", to make them feel involved.',
                'Expert opinion': 'Quoting someone with authority or expertise to add credibility to an argument.',
                'Hyperbole': 'Deliberate exaggeration used to make a point feel more dramatic and persuasive.',
                'Alliteration': 'Repeating consonant sounds to make a phrase catchy and memorable.',
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
                storageKey: 'persuasive-techniquesGame.settings',
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
                        questionText: "What is '" + term + "'?",
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about how a writer or speaker might try to win over an audience emotionally or logically.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered persuasive techniques!",
            });
        })();
    </script>
@endpush
