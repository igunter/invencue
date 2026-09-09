@extends('layouts.app')

@section('meta_title', 'Algorithms & Flowcharts — Computing Game for Kids')
@section('meta_blurb', 'A free computing game — learn sequence, selection and iteration, and the key flowchart symbols.')
@section('meta_words', 'algorithms game, flowcharts game, sequence selection iteration, ks3 computing game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-diagram-3',
        'title' => 'Algorithms & Flowcharts',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Algorithms & flowcharts'],
        ],
        'aboutTitle' => 'About this algorithms & flowcharts game',
        'aboutText' => 'This free computing game covers algorithm key terms — sequence, selection and iteration — and the standard flowchart symbols used to plan out step-by-step instructions.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What do we call a set of step-by-step instructions to solve a problem?", a: "An algorithm" },
                { q: "What is it called when instructions run in the order they are written, one after another?", a: "Sequence" },
                { q: "What is it called when a program makes a decision, like using an IF statement?", a: "Selection" },
                { q: "What is it called when a set of instructions repeats?", a: "Iteration (a loop)" },
                { q: "In a flowchart, what shape usually shows the start or end of a process?", a: "An oval (rounded rectangle)" },
                { q: "In a flowchart, what shape shows a step or process?", a: "A rectangle" },
                { q: "In a flowchart, what shape shows a decision, like a yes/no question?", a: "A diamond (rhombus)" },
                { q: "In a flowchart, what shape shows an input or output, like entering or displaying data?", a: "A parallelogram" },
                { q: "What do the arrows in a flowchart show?", a: "The order (flow) the steps happen in" },
                { q: "What is it called when you find and fix an error in an algorithm?", a: "Debugging" },
                { q: "What is it called when you check an algorithm works correctly?", a: "Testing" },
                { q: "What is a loop that repeats a set number of times called?", a: "A count-controlled loop (FOR loop)" },
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
                storageKey: 'algorithmsFlowchartsGame.settings',
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
                    return 'Think about whether this is about order, a decision, repeating, or a flowchart shape.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're an algorithms & flowcharts superstar!",
            });
        })();
    </script>
@endpush
