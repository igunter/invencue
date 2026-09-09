@extends('layouts.app')

@section('meta_title', 'Biomes of the World — Geography Game for Kids')
@section('meta_blurb', 'A free geography game — learn about rainforests, deserts, tundra, savanna and other biomes and their features.')
@section('meta_words', 'biomes of the world game, geography game for kids, rainforest desert tundra savanna, world biomes quiz, ks2 geography')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-tree',
        'title' => 'Biomes of the World',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'World biomes'],
        ],
        'aboutTitle' => 'About this biomes of the world game',
        'aboutText' => 'This free geography game helps kids learn about the world\'s major biomes — rainforest, desert, tundra, savanna and more — and the climate and living things that make each one different.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "Which biome is hot and very wet all year round, with thick, tall trees and the most species of any biome?", a: "Tropical rainforest" },
                { q: "Which biome is extremely dry, with very little rainfall, and can be hot or cold?", a: "Desert" },
                { q: "Which biome is very cold, has frozen ground called permafrost, and almost no trees?", a: "Tundra" },
                { q: "Which biome is a hot grassland with scattered trees, home to lions and zebras in Africa?", a: "Savanna" },
                { q: "Which biome has warm summers, cold winters, and trees that lose their leaves in autumn?", a: "Temperate (deciduous) forest" },
                { q: "Which biome is a large, flat grassland with few trees, found in the middle of continents?", a: "Grassland (steppe)" },
                { q: "Which biome is a cold forest of mostly evergreen trees, found across northern Canada and Russia?", a: "Taiga (boreal forest)" },
                { q: "Which biome is permanently covered in ice, with almost no plant life, found at the poles?", a: "Polar (ice cap)" },
                { q: "Which biome is land that is regularly flooded with water, such as marshes and swamps?", a: "Wetland" },
                { q: "In which biome would you expect to find cactus plants adapted to store water?", a: "Desert" },
                { q: "In which biome would you expect to find polar bears and reindeer?", a: "Tundra" },
                { q: "In which biome would you expect to find the greatest number of different plant and animal species?", a: "Tropical rainforest" },
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
                storageKey: 'biomesOfTheWorldGame.settings',
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
                    return 'Think about the temperature, rainfall, and the plants or animals described.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a world biomes superstar!",
            });
        })();
    </script>
@endpush
