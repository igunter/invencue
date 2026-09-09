@extends('layouts.app')

@section('meta_title', 'Sleep & the Brain — Psychology Game for Kids')
@section('meta_blurb', 'A free psychology game covering why we sleep and what happens in the brain during sleep.')
@section('meta_words', 'sleep and the brain game, why we sleep, sleep facts for kids, psychology game for kids')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-moon-stars',
        'title' => 'Sleep & the Brain',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Sleep & the brain'],
        ],
        'aboutTitle' => 'About this sleep & the brain game',
        'aboutText' => 'This free game covers why sleep matters for the brain and body, including sleep cycles, REM sleep, and the effect that too little sleep can have on mood and concentration.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "Roughly how many hours of sleep do most 10-13 year olds need each night?", a: "About 9 to 11 hours" },
                { q: "What is the name for the different stages your brain and body cycle through during sleep?", a: "Sleep cycles" },
                { q: "During which stage of sleep do most vivid dreams happen?", a: "REM sleep" },
                { q: "What does 'REM' stand for in REM sleep?", a: "Rapid eye movement" },
                { q: "Why is sleep important for memory?", a: "It helps the brain store and organise what you learned that day" },
                { q: "What hormone released in the evening helps make you feel sleepy?", a: "Melatonin" },
                { q: "What can too much screen time before bed do to your sleep?", a: "Make it harder to fall asleep" },
                { q: "What is it called when you don't get enough sleep over time?", a: "Sleep deprivation" },
                { q: "Why does your brain need deep sleep, not just any sleep?", a: "It helps the body repair itself and the brain recover" },
                { q: "What is a consistent bedtime routine useful for?", a: "Helping your body know when it's time to sleep" },
                { q: "Which part of the day is your body's internal clock, or 'body clock', linked to?", a: "The 24-hour day-night cycle" },
                { q: "What can happen to concentration and mood after a poor night's sleep?", a: "They can both get worse" },
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
                storageKey: 'sleepAndTheBrainGame.settings',
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
                    return 'Think about what the brain and body need to rest and recover.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a sleep and brain expert!",
            });
        })();
    </script>
@endpush
