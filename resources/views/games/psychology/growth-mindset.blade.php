@extends('layouts.app')

@section('meta_title', 'Growth Mindset — Psychology Game for Kids')
@section('meta_blurb', 'A free psychology game for kids — practise growth mindset thinking about effort, mistakes and "yet".')
@section('meta_words', 'growth mindset game for kids, yet thinking, learning from mistakes, effort game, psychology game for kids')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-lightbulb',
        'title' => 'Growth Mindset',
        'subtitle' => 'Read the clue, then pick the growth mindset answer!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Growth mindset'],
        ],
        'aboutTitle' => 'About this growth mindset game',
        'aboutText' => 'This free game helps young kids practise growth mindset thinking — the idea that effort and practice help you improve, and that mistakes are simply a normal part of learning.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "Maddie says 'I can't do this maths problem... yet.' What is this an example of?", a: "A growth mindset" },
                { q: "What should you say to yourself when something feels too hard right now?", a: "I can't do this yet" },
                { q: "Which is a growth mindset way to think about a mistake?", a: "Mistakes help me learn" },
                { q: "Sam gets a question wrong on a test. What's the best next step with a growth mindset?", a: "Try to understand what went wrong and learn from it" },
                { q: "What matters most for getting better at something, according to growth mindset thinking?", a: "Practising and putting in effort" },
                { q: "Which sentence shows a fixed mindset instead of a growth mindset?", a: "I'll never be good at this, so why try" },
                { q: "Why is practising a new skill important?", a: "It helps your brain get better at it, even if it's hard at first" },
                { q: "What should you do if your first attempt at something doesn't work?", a: "Try again a different way" },
                { q: "How should you feel about trying something new and difficult?", a: "It's OK to find it hard — that's how you learn" },
                { q: "What's a growth mindset way to talk about someone else's mistake?", a: "Everyone makes mistakes while they're learning" },
                { q: "Which word describes trying hard even when something is difficult?", a: "Effort" },
                { q: "What can you learn from getting something wrong?", a: "What to try differently next time" },
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
                storageKey: 'growthMindsetGame.settings',
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
                    return 'Think about how your brain grows stronger with practice, like a muscle.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You've got a brilliant growth mindset!",
            });
        })();
    </script>
@endpush
