@extends('layouts.app')

@section('meta_title', 'Continents & Oceans — Geography Game for Kids')
@section('meta_blurb', 'A free geography game for young kids — learn the 7 continents and 5 oceans of the world.')
@section('meta_words', 'continents and oceans game, geography game for kids, seven continents, five oceans, ks1 ks2 geography')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-globe-americas',
        'title' => 'Continents & Oceans',
        'subtitle' => 'Pick your question types, then test your world knowledge!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'continents', 'label' => 'Continents'],
            ['id' => 'oceans', 'label' => 'Oceans'],
        ],
        'aboutTitle' => 'About this continents & oceans game',
        'aboutText' => 'This free geography game helps young kids learn the 7 continents and 5 oceans of the world, and a few fun facts about each one. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const CONTINENTS = [
                { q: "Which is the largest continent on Earth by area?", a: "Asia" },
                { q: "Which continent is Egypt located on?", a: "Africa" },
                { q: "Which continent is the United Kingdom part of?", a: "Europe" },
                { q: "Which continent is also known as the 'smallest continent'?", a: "Australia" },
                { q: "Which continent is almost entirely covered in ice and has no permanent population?", a: "Antarctica" },
                { q: "Which continent is Brazil located on?", a: "South America" },
                { q: "Which continent is the USA and Canada part of?", a: "North America" },
                { q: "How many continents are there in total?", a: "Seven" },
                { q: "Which continent is home to the Sahara, the world's largest hot desert?", a: "Africa" },
                { q: "Which continent is Japan and China part of?", a: "Asia" },
                { q: "Which continent is most of the Amazon Rainforest found in?", a: "South America" },
                { q: "Which continent is the coldest, driest and windiest on Earth?", a: "Antarctica" },
            ];

            const OCEANS = [
                { q: "Which is the largest and deepest ocean on Earth?", a: "The Pacific Ocean" },
                { q: "Which ocean lies between the Americas and Europe/Africa?", a: "The Atlantic Ocean" },
                { q: "Which ocean surrounds the North Pole and is covered in sea ice?", a: "The Arctic Ocean" },
                { q: "Which ocean surrounds Antarctica?", a: "The Southern Ocean" },
                { q: "Which ocean lies between Africa, Asia and Australia?", a: "The Indian Ocean" },
                { q: "How many oceans are there in total?", a: "Five" },
                { q: "Which is the smallest of the world's oceans?", a: "The Arctic Ocean" },
                { q: "Which ocean lies along the west coast of the USA?", a: "The Pacific Ocean" },
                { q: "Which ocean lies along the east coast of the USA?", a: "The Atlantic Ocean" },
                { q: "Which ocean is the warmest on average, and touches eastern Africa, southern Asia and western Australia?", a: "The Indian Ocean" },
                { q: "Which ocean would you cross to sail directly between Australia and Antarctica?", a: "The Southern Ocean" },
                { q: "Which ocean is bordered by the Sahara Desert to its east?", a: "The Atlantic Ocean" },
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

            function bankFor(type) {
                return type === 'continents' ? CONTINENTS : OCEANS;
            }

            window.ScienceQuiz.run({
                storageKey: 'continentsOceansGame.settings',
                types: ['continents', 'oceans'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const bank = bankFor(type);
                    const item = bank[randInt(0, bank.length - 1)];
                    return { category: type, correctText: item.a, questionText: item.q };
                },

                buildChoices: function(q) {
                    const bank = bankFor(q.category);
                    const distractors = shuffle(
                        bank.map(function(f) { return f.a; }).filter(function(a) { return a !== q.correctText; })
                    ).slice(0, 3);
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'continents') {
                        return 'Think about which landmass this country, desert or rainforest sits on.';
                    }
                    return 'Think about which coastlines or poles this body of water touches.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a continents and oceans superstar!",
            });
        })();
    </script>
@endpush
