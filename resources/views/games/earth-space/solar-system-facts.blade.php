@extends('layouts.app')

@section('meta_title', 'Solar System Facts — Kids Earth Science Game')
@section('meta_blurb', 'A free earth science game for kids — learn which planet is biggest, smallest, hottest, and how many moons each planet has.')
@section('meta_words', 'solar system facts game, kids earth science game, biggest smallest planet, planet moons, jupiter saturn mercury')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-globe2',
        'title' => 'Solar System Facts',
        'subtitle' => 'Pick your question types, then test your planet knowledge!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'comparisons', 'label' => 'Biggest, smallest & hottest'],
            ['id' => 'moons', 'label' => 'How many moons?'],
        ],
        'aboutTitle' => 'About this solar system facts game',
        'aboutText' => 'This free earth science game covers which planet is the largest, smallest, hottest and furthest away, plus roughly how many moons each planet has. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const COMPARISONS = {
                'The largest planet': 'Jupiter',
                'The smallest planet': 'Mercury',
                'The hottest planet': 'Venus',
                'The planet with the most moons': 'Saturn',
                'The closest planet to the Sun': 'Mercury',
                'The furthest planet from the Sun': 'Neptune',
                'The coldest planet': 'Uranus',
                'The windiest planet': 'Neptune',
                'The planet with the most visible rings': 'Saturn',
                'The planet known as the Red Planet': 'Mars',
            };
            const COMPARISON_NAMES = Object.keys(COMPARISONS);
            const PLANET_LIST = ['Mercury', 'Venus', 'Earth', 'Mars', 'Jupiter', 'Saturn', 'Uranus', 'Neptune'];

            const MOONS = {
                'Mercury': '0 moons',
                'Venus': '0 moons',
                'Earth': '1 moon',
                'Mars': '2 moons',
                'Jupiter': 'Over 90 moons',
                'Saturn': 'Over 140 moons',
                'Uranus': 'Over 25 moons',
                'Neptune': 'Over 15 moons',
                'Pluto': '5 moons',
                'Ceres': '0 moons',
            };
            const MOON_PLANETS = Object.keys(MOONS);
            const MOON_COUNTS = ['0 moons', '1 moon', '2 moons', '5 moons', 'Over 15 moons', 'Over 25 moons', 'Over 90 moons', 'Over 140 moons'];

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
                storageKey: 'solarSystemFactsGame.settings',
                types: ['comparisons', 'moons'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'comparisons') {
                        const fact = COMPARISON_NAMES[randInt(0, COMPARISON_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: COMPARISONS[fact],
                            questionText: "Which planet is '" + fact + "'?",
                        };
                    }
                    const planet = MOON_PLANETS[randInt(0, MOON_PLANETS.length - 1)];
                    return {
                        category: type,
                        correctText: MOONS[planet],
                        questionText: 'Roughly how many moons does ' + planet + ' have?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'comparisons') {
                        const distractors = pickOthers(PLANET_LIST, q.correctText, 3);
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(MOON_COUNTS, q.correctText, 3);
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'comparisons') {
                        return 'Think about size, temperature, distance from the Sun, and which one has the most rings and moons.';
                    }
                    return 'Rocky planets close to the Sun tend to have very few moons; the huge gas giants have lots.';
                },

                explanationFor: function(q) {
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a solar system facts superstar!",
            });
        })();
    </script>
@endpush
