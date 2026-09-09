@extends('layouts.app')

@section('meta_title', 'Intro to Programming Terms — Computing Game for Kids')
@section('meta_blurb', 'A free computing game — learn block-coding terms like variable, loop, function, input and output.')
@section('meta_words', 'programming terms game, scratch coding quiz, variable loop function, ks3 computing game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-code-slash',
        'title' => 'Intro to Programming Terms',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Programming terms'],
        ],
        'aboutTitle' => 'About this intro to programming terms game',
        'aboutText' => 'This free computing game covers block-coding level programming terms — variables, loops, functions, inputs and outputs — the kind of vocabulary used in tools like Scratch.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What do we call a container that stores a value which can change, like a score?", a: "A variable" },
                { q: "What do we call a block of code that repeats a set of instructions?", a: "A loop" },
                { q: "What do we call a named block of code that performs a task and can be reused?", a: "A function (block/procedure)" },
                { q: "What word describes information that is put into a program, like a typed answer?", a: "Input" },
                { q: "What word describes information a program shows or produces, like text on screen?", a: "Output" },
                { q: "In Scratch, what do we call the coloured puzzle pieces you snap together to build a program?", a: "Blocks" },
                { q: "What do we call it when a program checks if something is true before doing an action?", a: "A condition (an if statement)" },
                { q: "What is the character or object that follows your code's instructions in Scratch called?", a: "A sprite" },
                { q: "What do we call an error in a program that stops it working correctly?", a: "A bug" },
                { q: "What is it called when you find and fix a bug in code?", a: "Debugging" },
                { q: "What does a variable that stores a whole number, like 5, hold?", a: "An integer (a number)" },
                { q: "What is it called when a program starts running from the beginning?", a: "Running (executing) the program" },
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
                storageKey: 'introToProgrammingTermsGame.settings',
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
                    return 'Think about whether this is about storing data, repeating steps, or making decisions.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a programming terms superstar!",
            });
        })();
    </script>
@endpush
