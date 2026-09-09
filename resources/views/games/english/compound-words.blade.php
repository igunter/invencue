@extends('layouts.app')

@section('meta_title', 'Compound Words — English Game for Kids')
@section('meta_blurb', 'A free English game for kids — join two small words together to build a compound word.')
@section('meta_words', 'compound words game, word building game for kids, vocabulary game, ks1 english game, ks2 english game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-puzzle',
        'title' => 'Compound Words',
        'subtitle' => 'Join the two words, then pick the compound word!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Compound words'],
        ],
        'aboutTitle' => 'About this compound words game',
        'aboutText' => 'This free English game helps young kids build compound words by joining two smaller words together, like "sun" and "flower" to make "sunflower".',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "Which word do you get when you join 'sun' and 'flower'?", a: "Sunflower" },
                { q: "Which word do you get when you join 'foot' and 'ball'?", a: "Football" },
                { q: "Which word do you get when you join 'rain' and 'bow'?", a: "Rainbow" },
                { q: "Which word do you get when you join 'butter' and 'fly'?", a: "Butterfly" },
                { q: "Which word do you get when you join 'snow' and 'man'?", a: "Snowman" },
                { q: "Which word do you get when you join 'tooth' and 'brush'?", a: "Toothbrush" },
                { q: "Which word do you get when you join 'back' and 'pack'?", a: "Backpack" },
                { q: "Which word do you get when you join 'star' and 'fish'?", a: "Starfish" },
                { q: "Which word do you get when you join 'bed' and 'room'?", a: "Bedroom" },
                { q: "Which word do you get when you join 'cup' and 'cake'?", a: "Cupcake" },
                { q: "Which word do you get when you join 'play' and 'ground'?", a: "Playground" },
                { q: "Which word do you get when you join 'fire' and 'fly'?", a: "Firefly" },
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
                storageKey: 'compound-wordsGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const item = FACTS[randInt(0, FACTS.length - 1)];
                    return { category: type, correctText: item.a, questionText: item.q };
                },

                buildChoices: function(q) {
                    const distractors = shuffle(
                        FACTS.map(function(f) { return f.a; }).filter(function(a) { return a !== q.correctText; })
                    ).slice(0, 3);
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Say the two small words quickly, one after the other.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a compound words superstar!",
            });
        })();
    </script>
@endpush
