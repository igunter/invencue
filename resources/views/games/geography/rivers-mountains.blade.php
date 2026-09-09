@extends('layouts.app')

@section('meta_title', 'Rivers & Mountains — Geography Game for Kids')
@section('meta_blurb', 'A free geography game — learn famous rivers and mountain ranges and which continent or country each is in.')
@section('meta_words', 'rivers and mountains game, geography game for kids, famous rivers, famous mountain ranges, ks2 geography')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-water',
        'title' => 'Rivers & Mountains',
        'subtitle' => 'Pick your question types, then test your world geography knowledge!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'rivers', 'label' => 'Rivers'],
            ['id' => 'mountains', 'label' => 'Mountains'],
        ],
        'aboutTitle' => 'About this rivers & mountains game',
        'aboutText' => 'This free geography game helps kids learn some of the world\'s most famous rivers and mountain ranges, and which continent or country each one is found in. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const RIVERS = [
                { q: "The River Nile, one of the longest rivers in the world, flows through which continent?", a: "Africa" },
                { q: "The Amazon River, which carries more water than any other river, flows through which continent?", a: "South America" },
                { q: "Which river flows through London?", a: "The River Thames" },
                { q: "The Mississippi River is one of the major rivers of which country?", a: "The USA" },
                { q: "The Yangtze, the longest river in Asia, flows through which country?", a: "China" },
                { q: "The Ganges, considered sacred by Hindus, flows mainly through which country?", a: "India" },
                { q: "Which river forms a very large delta as it flows into the North Sea through the Netherlands?", a: "The Rhine" },
                { q: "The Danube flows through many countries in which continent?", a: "Europe" },
                { q: "The Amazon River flows into which ocean?", a: "The Atlantic Ocean" },
                { q: "The River Nile flows into which sea?", a: "The Mediterranean Sea" },
                { q: "Which river flows through Paris?", a: "The River Seine" },
                { q: "The Zambezi River, home to Victoria Falls, flows through which continent?", a: "Africa" },
            ];

            const MOUNTAINS = [
                { q: "The Himalayas, home to the world's highest peaks, are found mainly in which continent?", a: "Asia" },
                { q: "Mount Everest, the world's highest mountain above sea level, sits on the border of Nepal and which other country?", a: "China (Tibet)" },
                { q: "The Andes, the world's longest mountain range, run down the west coast of which continent?", a: "South America" },
                { q: "The Alps, a famous mountain range with ski resorts, stretch across several countries in which continent?", a: "Europe" },
                { q: "The Rocky Mountains run down the western side of which continent?", a: "North America" },
                { q: "Mount Kilimanjaro, the highest mountain in Africa, is found in which country?", a: "Tanzania" },
                { q: "The Ural Mountains are often used to mark the boundary between Europe and which other continent?", a: "Asia" },
                { q: "Mont Blanc, the highest mountain in the Alps, sits on the border of France and which other country?", a: "Italy" },
                { q: "The Atlas Mountains stretch across the north-west of which continent?", a: "Africa" },
                { q: "The Great Dividing Range, the longest mountain range in Australia, runs along which coast of the country?", a: "The east coast" },
                { q: "The Appalachian Mountains run down the eastern side of which continent?", a: "North America" },
                { q: "Mount Fuji, an iconic snow-capped volcano, is found in which country?", a: "Japan" },
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
                return type === 'rivers' ? RIVERS : MOUNTAINS;
            }

            window.ScienceQuiz.run({
                storageKey: 'riversMountainsGame.settings',
                types: ['rivers', 'mountains'],
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
                    if (q.category === 'rivers') {
                        return 'Think about which continent or ocean this river is linked to.';
                    }
                    return 'Think about which continent or which famous peak this mountain range is linked to.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a rivers and mountains superstar!",
            });
        })();
    </script>
@endpush
