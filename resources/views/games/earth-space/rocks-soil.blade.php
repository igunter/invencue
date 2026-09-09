@extends('layouts.app')

@section('meta_title', 'Rocks & Soil — Kids Earth Science Game')
@section('meta_blurb', 'A free earth science game for young kids — sort rocks by how they look, and learn about clay, sandy and loam soil.')
@section('meta_words', 'rocks and soil game, kids earth science game, granite chalk slate sandstone, clay sandy loam soil')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-box-seam',
        'title' => 'Rocks & Soil',
        'subtitle' => 'Pick your question types, then test your rocks and soil knowledge!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'rocks', 'label' => 'What does it look like?'],
            ['id' => 'soil', 'label' => 'Soil facts'],
        ],
        'aboutTitle' => 'About this rocks & soil game',
        'aboutText' => 'This free earth science game helps young kids learn what different rocks look like — granite, chalk, slate, sandstone and pumice — and the differences between clay, sandy and loam soil. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const ROCKS = {
                'Granite': 'A hard, speckled rock often used for kitchen worktops and buildings.',
                'Chalk': 'A soft, white rock that crumbles easily and is used to make writing chalk.',
                'Slate': 'A flat, grey rock that splits easily into thin sheets, often used for roof tiles.',
                'Sandstone': 'A rock made of grains of sand pressed together, often orange or yellow.',
                'Pumice': 'A light, bumpy rock full of holes, made from cooled volcanic lava — it can even float!',
            };
            const ROCK_NAMES = Object.keys(ROCKS);

            const SOIL = {
                'Clay soil': 'Soil made of very fine particles — it holds water well but drains slowly.',
                'Sandy soil': "Soil made of large particles — it drains quickly but doesn't hold water or nutrients well.",
                'Loam soil': "A mix of sand, silt and clay — it's great for growing most plants.",
            };
            const SOIL_NAMES = Object.keys(SOIL);

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

            function pickOthers(pool, exclude, count) {
                return shuffle(pool.filter(function(x) { return x !== exclude; })).slice(0, count);
            }

            window.ScienceQuiz.run({
                storageKey: 'rocksSoilGame.settings',
                types: ['rocks', 'soil'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'rocks') {
                        const rock = ROCK_NAMES[randInt(0, ROCK_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: ROCKS[rock],
                            questionText: 'What does ' + rock.toLowerCase() + ' look like?',
                        };
                    }
                    const soil = SOIL_NAMES[randInt(0, SOIL_NAMES.length - 1)];
                    return {
                        category: type,
                        correctText: SOIL[soil],
                        questionText: "What is '" + soil + "' like?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'rocks') {
                        const rock = ROCK_NAMES.find(function(r) { return ROCKS[r] === q.correctText; });
                        const distractors = pickOthers(ROCK_NAMES, rock, 3).map(function(r) { return ROCKS[r]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const soil = SOIL_NAMES.find(function(s) { return SOIL[s] === q.correctText; });
                    const distractors = pickOthers(SOIL_NAMES, soil, 2).map(function(s) { return SOIL[s]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'rocks') {
                        return 'Think about hardness, colour, whether it splits into sheets, or whether it is full of holes.';
                    }
                    return 'Think about how easily water drains through it, and how well it holds onto water and nutrients.';
                },

                explanationFor: function(q) {
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a rocks and soil superstar!",
            });
        })();
    </script>
@endpush
