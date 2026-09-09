@extends('layouts.app')

@section('meta_title', 'Similes & Metaphors — English Game for Kids')
@section('meta_blurb', 'A free English game for kids — decide whether a sentence uses a simile or a metaphor.')
@section('meta_words', 'similes and metaphors game, figurative language game, ks2 english game, ks3 english game, comparisons game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-stars',
        'title' => 'Similes & Metaphors',
        'subtitle' => 'Read the sentence, then say which type it is!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Simile or metaphor?'],
        ],
        'aboutTitle' => 'About this similes & metaphors game',
        'aboutText' => 'This free English game helps you spot the difference between similes, which compare using "like" or "as", and metaphors, which describe one thing as if it actually is something else.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const SENTENCES = {
                'Her smile was like sunshine.': 'Simile',
                'He ran as fast as a cheetah.': 'Simile',
                'The classroom was as quiet as a library.': 'Simile',
                'Life is like a box of chocolates.': 'Simile',
                'The water was as cold as ice.': 'Simile',
                'He fought like a lion.': 'Simile',
                'Time is a thief.': 'Metaphor',
                'The classroom was a zoo.': 'Metaphor',
                'Her heart is a stone.': 'Metaphor',
                'The world is a stage.': 'Metaphor',
                'He has a heart of gold.': 'Metaphor',
                'The snow was a white blanket over the town.': 'Metaphor',
            };
            const SENTENCE_NAMES = Object.keys(SENTENCES);

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

            window.ScienceQuiz.run({
                storageKey: 'similes-and-metaphorsGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const sentence = SENTENCE_NAMES[randInt(0, SENTENCE_NAMES.length - 1)];
                    return {
                        category: type,
                        correctText: SENTENCES[sentence],
                        questionText: "'" + sentence + "' — is this a simile or a metaphor?",
                    };
                },

                buildChoices: function() {
                    return shuffle(['Simile', 'Metaphor']);
                },

                hintFor: function() {
                    return 'Does the sentence use the words "like" or "as" to compare? That makes it a simile.';
                },

                explanationFor: function(q) {
                    return q.correctText === 'Simile'
                        ? "It's a simile because it compares using 'like' or 'as'."
                        : "It's a metaphor because it describes something as if it actually is something else.";
                },

                masteryMessage: "Amazing! You're a similes and metaphors superstar!",
            });
        })();
    </script>
@endpush
