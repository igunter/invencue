@extends('layouts.app')

@section('meta_title', 'Countries of the UK — Geography Game for Kids')
@section('meta_blurb', 'A free geography game for young kids — learn about England, Scotland, Wales and Northern Ireland.')
@section('meta_words', 'countries of the uk game, geography game for kids, england scotland wales northern ireland, ks1 ks2 geography')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-flag',
        'title' => 'Countries of the UK',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'UK facts'],
        ],
        'aboutTitle' => 'About this countries of the UK game',
        'aboutText' => 'This free geography game helps young kids learn about the four countries that make up the United Kingdom — England, Scotland, Wales and Northern Ireland — including their capital cities, flags and famous symbols.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What is the capital city of England?", a: "London" },
                { q: "What is the capital city of Scotland?", a: "Edinburgh" },
                { q: "What is the capital city of Wales?", a: "Cardiff" },
                { q: "What is the capital city of Northern Ireland?", a: "Belfast" },
                { q: "How many countries make up the United Kingdom?", a: "Four" },
                { q: "Which UK country's flag shows a red dragon?", a: "Wales" },
                { q: "Which UK country's flag is a white saltire cross on a blue background?", a: "Scotland" },
                { q: "Which UK country's flag is a red cross on a white background?", a: "England" },
                { q: "The daffodil and the leek are national symbols of which UK country?", a: "Wales" },
                { q: "The thistle is a national symbol of which UK country?", a: "Scotland" },
                { q: "The shamrock is a symbol often linked with which UK country?", a: "Northern Ireland" },
                { q: "Which UK country is home to Loch Ness?", a: "Scotland" },
                { q: "Which UK country is home to Mount Snowdon, one of the highest peaks in the UK?", a: "Wales" },
                { q: "Which UK country shares a land border with the Republic of Ireland?", a: "Northern Ireland" },
                { q: "Which UK country is the largest by both area and population?", a: "England" },
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
                storageKey: 'countriesOfTheUkGame.settings',
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
                    return 'Think about which of the four UK countries this belongs to: England, Scotland, Wales or Northern Ireland.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a UK geography superstar!",
            });
        })();
    </script>
@endpush
