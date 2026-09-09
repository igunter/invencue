@extends('layouts.app')

@section('meta_title', 'Homophones — English Game for Kids')
@section('meta_blurb', 'A free English game for kids — choose the correct homophone to complete each sentence.')
@section('meta_words', 'homophones game, their there they\'re game, to too two game, ks2 english game, spelling game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-ear',
        'title' => 'Homophones',
        'subtitle' => 'Read the sentence, then pick the correct word!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Homophones'],
        ],
        'aboutTitle' => 'About this homophones game',
        'aboutText' => 'This free English game tests tricky homophones — words that sound the same but are spelled differently and mean different things, like "their", "there" and "they\'re", or "to", "too" and "two".',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "I saw ___ dog running in the park.", a: "their", options: ["their", "there", "they're"] },
                { q: "Put the book over ___.", a: "there", options: ["their", "there", "they're"] },
                { q: "___ going to the beach tomorrow.", a: "They're", options: ["Their", "There", "They're"] },
                { q: "Please give the pen ___ me.", a: "to", options: ["to", "too", "two"] },
                { q: "I have ___ apples in my bag.", a: "two", options: ["to", "too", "two"] },
                { q: "She wanted to come ___.", a: "too", options: ["to", "too", "two"] },
                { q: "Is this ___ coat?", a: "your", options: ["your", "you're"] },
                { q: "___ going to be late if we don't hurry.", a: "You're", options: ["Your", "You're"] },
                { q: "The dog wagged ___ tail.", a: "its", options: ["its", "it's"] },
                { q: "___ raining outside.", a: "It's", options: ["Its", "It's"] },
                { q: "I need to ___ a jumper because it's cold.", a: "wear", options: ["wear", "where"] },
                { q: "___ are you going?", a: "Where", options: ["Wear", "Where"] },
            ];

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
                storageKey: 'homophonesGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const item = FACTS[randInt(0, FACTS.length - 1)];
                    return {
                        category: type,
                        correctText: item.a,
                        options: item.options,
                        questionText: item.q,
                    };
                },

                buildChoices: function(q) {
                    return shuffle(q.options);
                },

                hintFor: function() {
                    return 'Read the whole sentence and think about what the word actually means here.';
                },

                explanationFor: function(q) {
                    return "'" + q.correctText + "' is correct here.";
                },

                masteryMessage: "Amazing! You're a homophones superstar!",
            });
        })();
    </script>
@endpush
