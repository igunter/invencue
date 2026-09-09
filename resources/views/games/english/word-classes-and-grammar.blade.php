@extends('layouts.app')

@section('meta_title', 'Word Classes & Grammar — GCSE English Game')
@section('meta_blurb', 'A free GCSE English game covering grammar terms and active vs passive voice.')
@section('meta_words', 'word classes game, grammar game gcse, active passive voice, clauses game, gcse english language game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-braces',
        'title' => 'Word Classes & Grammar',
        'subtitle' => 'Pick your question types, then test your grammar!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'terms', 'label' => 'Grammar terms'],
            ['id' => 'voice', 'label' => 'Active or passive?'],
        ],
        'aboutTitle' => 'About this word classes & grammar game',
        'aboutText' => 'This free GCSE English game covers key grammar terminology — clauses, sentence types, conjunctions and more — and tests whether you can spot active and passive voice in a sentence. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Main clause': 'A clause that makes complete sense on its own and could stand as a sentence.',
                'Subordinate clause': 'A clause that adds extra information but cannot stand alone as a full sentence.',
                'Simple sentence': 'A sentence made of just one main clause.',
                'Compound sentence': 'Two main clauses joined by a conjunction such as "and", "but" or "or".',
                'Complex sentence': 'A sentence made of a main clause and at least one subordinate clause.',
                'Conjunction': 'A word that joins clauses or words together, such as "and", "because" or "although".',
                'Preposition': 'A word that shows the relationship between a noun and other words, such as "on", "under" or "before".',
                'Pronoun': 'A word used in place of a noun, such as "he", "she" or "it".',
                'Adverb': 'A word that describes a verb, adjective or another adverb, often ending in "-ly".',
                'Determiner': 'A word placed before a noun to clarify what it refers to, such as "the", "a" or "this".',
            };
            const TERM_NAMES = Object.keys(TERMS);

            const VOICE = {
                'The dog chased the ball.': 'Active',
                'The ball was chased by the dog.': 'Passive',
                'The chef cooked the meal.': 'Active',
                'The meal was cooked by the chef.': 'Passive',
                'The teacher marked the essays.': 'Active',
                'The essays were marked by the teacher.': 'Passive',
                'Shakespeare wrote the play.': 'Active',
                'The play was written by Shakespeare.': 'Passive',
                'The storm damaged the roof.': 'Active',
                'The roof was damaged by the storm.': 'Passive',
            };
            const VOICE_NAMES = Object.keys(VOICE);

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
                storageKey: 'word-classes-and-grammarGame.settings',
                types: ['terms', 'voice'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'terms') {
                        const term = TERM_NAMES[randInt(0, TERM_NAMES.length - 1)];
                        return {
                            category: type,
                            label: term,
                            correctText: TERMS[term],
                            questionText: "What is a '" + term.toLowerCase() + "'?",
                        };
                    }
                    const sentence = VOICE_NAMES[randInt(0, VOICE_NAMES.length - 1)];
                    return {
                        category: type,
                        correctText: VOICE[sentence],
                        questionText: "'" + sentence + "' — is this sentence active or passive voice?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'terms') {
                        const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    return shuffle(['Active', 'Passive']);
                },

                hintFor: function(q) {
                    if (q.category === 'terms') return 'Think about how clauses and word classes fit together to build sentences.';
                    return "In an active sentence, the subject does the action. In a passive sentence, the subject has the action done to it — look for 'by'.";
                },

                explanationFor: function(q) {
                    if (q.category === 'terms') return q.label + ': ' + q.correctText;
                    return q.correctText === 'Active'
                        ? 'The subject is doing the action, so this is active voice.'
                        : 'The subject is having the action done to it, so this is passive voice.';
                },

                masteryMessage: "Amazing! You've mastered word classes and grammar!",
            });
        })();
    </script>
@endpush
