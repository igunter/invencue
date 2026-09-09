@extends('layouts.app')

@section('meta_title', 'World Capitals — Geography Game for Kids')
@section('meta_blurb', 'A free geography game — match countries to their capital cities.')
@section('meta_words', 'world capitals game, geography game for kids, capital cities quiz, countries and capitals, ks2 geography')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-pin-map',
        'title' => 'World Capitals',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'World capitals'],
        ],
        'aboutTitle' => 'About this world capitals game',
        'aboutText' => 'This free geography game helps kids match countries from around the world to their capital cities, building up a picture of world geography one country at a time.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What is the capital city of France?", a: "Paris" },
                { q: "What is the capital city of Japan?", a: "Tokyo" },
                { q: "What is the capital city of Egypt?", a: "Cairo" },
                { q: "What is the capital city of Australia?", a: "Canberra" },
                { q: "What is the capital city of Canada?", a: "Ottawa" },
                { q: "What is the capital city of Brazil?", a: "Brasília" },
                { q: "What is the capital city of India?", a: "New Delhi" },
                { q: "What is the capital city of Russia?", a: "Moscow" },
                { q: "What is the capital city of China?", a: "Beijing" },
                { q: "South Africa has three capital cities — which one is the administrative capital, home to the government?", a: "Pretoria" },
                { q: "What is the capital city of the United States?", a: "Washington, D.C." },
                { q: "What is the capital city of Mexico?", a: "Mexico City" },
                { q: "What is the capital city of Kenya?", a: "Nairobi" },
                { q: "What is the capital city of Argentina?", a: "Buenos Aires" },
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
                storageKey: 'worldCapitalsGame.settings',
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
                    return 'Think about which continent this country is on, and which city its government is run from.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a world capitals superstar!",
            });
        })();
    </script>
@endpush
