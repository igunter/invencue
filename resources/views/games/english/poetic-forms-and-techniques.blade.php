@extends('layouts.app')

@section('meta_title', 'Poetic Forms & Techniques — GCSE English Game')
@section('meta_blurb', 'A free GCSE English game covering poetic forms and techniques — sonnet, haiku, free verse, rhyme scheme and more.')
@section('meta_words', 'poetic forms game, poetry techniques game, gcse english literature game, sonnet haiku free verse, rhyme scheme stanza')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-journal-text',
        'title' => 'Poetic Forms & Techniques',
        'subtitle' => 'Read the term, then pick what it means!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Poetic forms & techniques'],
        ],
        'aboutTitle' => 'About this poetic forms & techniques game',
        'aboutText' => 'This free GCSE English game covers the forms and techniques used in poetry — sonnets, haikus, free verse, rhyme scheme, stanzas, enjambment, and more, ready for analysing poems in your English Literature exam.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Sonnet': 'A 14-line poem, often written in iambic pentameter, traditionally about love.',
                'Haiku': 'A three-line Japanese poem with a 5-7-5 syllable pattern.',
                'Free verse': 'Poetry with no fixed rhyme scheme or regular rhythm.',
                'Rhyme scheme': 'The pattern of rhymes at the end of each line in a poem, e.g. ABAB.',
                'Stanza': 'A group of lines in a poem, similar to a paragraph in prose.',
                'Enjambment': 'When a sentence or idea runs on from one line to the next without a pause.',
                'Ballad': 'A poem that tells a story, often set to music, usually with a regular rhyme and rhythm.',
                'Ode': 'A poem written in praise or celebration of a person, thing or idea.',
                'Caesura': 'A deliberate pause or break in the middle of a line of poetry.',
                'Iambic pentameter': "A rhythm of five pairs of unstressed and stressed syllables per line, common in Shakespeare's verse.",
                'Alliteration': 'Repetition of the same consonant sound at the start of nearby words.',
                'Assonance': 'Repetition of vowel sounds within nearby words.',
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
                storageKey: 'poetic-forms-and-techniquesGame.settings',
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
                        questionText: "What is a '" + term.toLowerCase() + "'?",
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about structure, rhythm, sound or the length of the poem.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered poetic forms and techniques!",
            });
        })();
    </script>
@endpush
