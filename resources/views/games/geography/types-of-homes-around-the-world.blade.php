@extends('layouts.app')

@section('meta_title', 'Types of Homes Around the World — Geography Game for Kids')
@section('meta_blurb', 'A free geography game for young kids — learn how homes around the world differ depending on climate and place.')
@section('meta_words', 'homes around the world game, geography game for kids, types of houses, climate and homes, ks1 ks2 geography')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-house-door',
        'title' => 'Types of Homes Around the World',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Homes around the world'],
        ],
        'aboutTitle' => 'About this types of homes around the world game',
        'aboutText' => 'This free geography game helps young kids learn how homes around the world differ depending on the climate and materials found in each place, from stilt houses to igloos.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What is the name for a traditional home built from blocks of hard snow, once used by some Arctic peoples?", a: "An igloo" },
                { q: "Why are homes in hot, sunny places often painted white?", a: "To reflect sunlight and help keep the inside cool" },
                { q: "What is the name for a home built up on tall posts above water or flood-prone ground?", a: "A stilt house" },
                { q: "Why might homes in very rainy places have steep, sloped roofs?", a: "So rain runs off quickly instead of collecting on the roof" },
                { q: "What is the name for a portable, round tent traditionally used by some nomadic peoples on the grasslands of Mongolia?", a: "A yurt" },
                { q: "Why do many desert homes have thick mud or clay walls?", a: "Thick walls keep the inside cool by day and warm at night" },
                { q: "What do we call people who move from place to place instead of living in one permanent home?", a: "Nomads" },
                { q: "In cold, snowy places, why do homes often have steeply sloped roofs?", a: "So heavy snow slides off instead of building up and causing damage" },
                { q: "What is the name for a tall block of flats where many families live in separate homes stacked on top of each other?", a: "An apartment block" },
                { q: "Why are homes near the equator often built with large windows and open sides?", a: "To let air flow through and help keep the home cool" },
                { q: "What natural material is commonly used to build homes in areas with large forests?", a: "Wood" },
                { q: "What do we call a home shared by an extended family, common in some rural farming communities?", a: "A family compound" },
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
                storageKey: 'typesOfHomesAroundTheWorldGame.settings',
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
                    return 'Think about the weather and materials found in that kind of place.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a homes around the world superstar!",
            });
        })();
    </script>
@endpush
