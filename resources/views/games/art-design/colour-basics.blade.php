@extends('layouts.app')

@section('meta_title', 'Colour Basics — Art Game for Kids')
@section('meta_blurb', 'A free art game for young kids covering primary and secondary colours, and what colours mix together to make.')
@section('meta_words', 'colour basics game, primary colours game, secondary colours quiz, kids art game, colour mixing')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-palette',
        'title' => 'Colour Basics',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Colour facts'],
        ],
        'aboutTitle' => 'About this colour basics game',
        'aboutText' => 'This free art game helps young kids learn the primary colours, the secondary colours they make when mixed, and what happens when colours are mixed with white or black.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What are the three primary colours?", a: "Red, blue and yellow" },
                { q: "Mix red and yellow together — what colour do you get?", a: "Orange" },
                { q: "Mix blue and yellow together — what colour do you get?", a: "Green" },
                { q: "Mix red and blue together — what colour do you get?", a: "Purple" },
                { q: "What do we call orange, green and purple?", a: "Secondary colours" },
                { q: "True or false: primary colours can be made by mixing other colours.", a: "False" },
                { q: "What happens when you mix a colour with white paint?", a: "It gets lighter" },
                { q: "What happens when you mix a colour with black paint?", a: "It gets darker" },
                { q: "Mix red and white together — what colour do you get?", a: "Pink" },
                { q: "Mix black and white together — what colour do you get?", a: "Grey" },
                { q: "How many primary colours are there?", a: "Three" },
                { q: "Which two primary colours make green?", a: "Blue and yellow" },
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
                storageKey: 'colour-basicsGame.settings',
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
                    return 'Think about which colours mix together to make new ones.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a colour basics superstar!",
            });
        })();
    </script>
@endpush
