@extends('layouts.app')

@section('meta_title', 'Programming Fundamentals — GCSE Computer Science Game')
@section('meta_blurb', 'A free GCSE computer science game covering variables, data types, operators, selection and iteration.')
@section('meta_words', 'programming fundamentals game, gcse computer science game, variables data types quiz, selection iteration')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-braces',
        'title' => 'Programming Fundamentals',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Programming fundamentals'],
        ],
        'aboutTitle' => 'About this programming fundamentals game',
        'aboutText' => 'This free GCSE computer science game covers core programming concepts at GCSE pseudocode level — variables, constants, data types, operators, selection and iteration.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What do we call a named storage location in a program that can hold a value which may change?", a: "A variable" },
                { q: "What do we call a value that is fixed and does not change while a program runs?", a: "A constant" },
                { q: "What data type stores whole numbers, like 5 or -3?", a: "Integer" },
                { q: "What data type stores numbers with decimal points, like 3.14?", a: "Real (float)" },
                { q: "What data type stores a single true or false value?", a: "Boolean" },
                { q: "What data type stores text, like a sentence or word?", a: "String" },
                { q: "What do we call symbols like +, - and *, used to perform calculations?", a: "Arithmetic operators" },
                { q: "What do we call operators like AND, OR and NOT, used to combine true/false conditions?", a: "Logical (Boolean) operators" },
                { q: "What construct lets a program choose between different paths depending on a condition, like IF...ELSE?", a: "Selection" },
                { q: "What construct repeats a block of code, like a FOR or WHILE loop?", a: "Iteration" },
                { q: "What do we call a loop that repeats while a condition remains true, checking before each repeat?", a: "A WHILE loop (pre-condition loop)" },
                { q: "What do we call an ordered list of items stored under a single variable name, accessed by index?", a: "An array" },
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
                storageKey: 'programmingFundamentalsGame.settings',
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
                    return 'Think about whether this is a data type, an operator, or a way of controlling program flow.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You've mastered programming fundamentals!",
            });
        })();
    </script>
@endpush
