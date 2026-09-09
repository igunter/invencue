@extends('layouts.app')

@section('meta_title', 'Great Inventions — History Game for Kids')
@section('meta_blurb', 'A free history game for kids — match famous inventors to what they invented.')
@section('meta_words', 'inventions game, history game for kids, famous inventors quiz, edison wright brothers, primary school history')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-lightbulb',
        'title' => 'Great Inventions',
        'subtitle' => 'Read the clue, then pick the right inventor!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Inventor facts'],
        ],
        'aboutTitle' => 'About this inventions game',
        'aboutText' => 'This free history game introduces kids to some famous inventors and the inventions that changed the world, from the light bulb to the World Wide Web.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "Who is best known for inventing the light bulb?", a: "Thomas Edison" },
                { q: "Who is best known for inventing the telephone?", a: "Alexander Graham Bell" },
                { q: "Who built and flew the first powered aeroplane?", a: "The Wright Brothers" },
                { q: "Who invented the printing press?", a: "Johannes Gutenberg" },
                { q: "Who invented the World Wide Web?", a: "Tim Berners-Lee" },
                { q: "Who built an improved steam engine that powered factories and trains?", a: "James Watt" },
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
                storageKey: 'great-inventionsGame.settings',
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
                    return 'Think carefully about the topic and time period.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're an inventions superstar!",
            });
        })();
    </script>
@endpush
