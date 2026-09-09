@extends('layouts.app')

@section('meta_title', 'Simple Coding Concepts — Computing Game for Kids')
@section('meta_blurb', 'A free computing game for kids — learn simple coding ideas like sequence and loops, no computer needed.')
@section('meta_words', 'simple coding concepts game, unplugged coding for kids, sequence loops game, ks1 ks2 computing, coding basics')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-arrow-repeat',
        'title' => 'Simple Coding Concepts',
        'subtitle' => 'Read the clue, then work out the coding idea!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Coding ideas'],
        ],
        'aboutTitle' => 'About this simple coding concepts game',
        'aboutText' => 'This free computing game introduces young kids to the building blocks of coding in a simple, unplugged way — following steps in order (sequence), repeating instructions (loops), and making decisions.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What do we call a list of steps that must be followed in order?", a: "A sequence" },
                { q: "If you repeat the same instruction over and over, what is that called?", a: "A loop" },
                { q: "What is it called when a computer program makes a decision, like 'if it's raining, take an umbrella'?", a: "Selection (a decision)" },
                { q: "In a set of instructions, what do we call one single step?", a: "An instruction (a step)" },
                { q: "What is the name for a set of step-by-step instructions to solve a problem?", a: "An algorithm" },
                { q: "If you told a robot 'turn right' 4 times to make a square, what coding idea would repeating it use?", a: "A loop" },
                { q: "What is it called when instructions are followed in the exact order they are written?", a: "Sequencing" },
                { q: "What do we call it when a program checks something is true before deciding what to do next?", a: "A condition (a decision)" },
                { q: "If a set of instructions has a mistake in it, what do we call fixing that mistake?", a: "Debugging" },
                { q: "What word describes breaking a big problem into smaller, easier parts?", a: "Decomposition" },
                { q: "What do we call testing your instructions to see if they work properly?", a: "Testing" },
                { q: "If you write instructions for making toast, what type of thinking are you using?", a: "Algorithmic thinking" },
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
                storageKey: 'simpleCodingConceptsGame.settings',
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
                    return 'Think about the order of steps, whether something repeats, or whether a decision is being made.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a coding ideas superstar!",
            });
        })();
    </script>
@endpush
