@extends('layouts.app')

@section('meta_title', 'Space & Orbits — Kids Physics Game')
@section('meta_blurb', 'A free physics game for kids — put the planets in order from the Sun and learn facts about each one.')
@section('meta_words', 'space and orbits game, kids physics game, solar system order, planet facts, mercury venus earth mars jupiter saturn')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-globe2',
        'title' => 'Space & Orbits',
        'subtitle' => 'Pick your question types, then test your space knowledge!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'order', 'label' => 'Order from the Sun'],
            ['id' => 'facts', 'label' => 'Planet facts'],
        ],
        'aboutTitle' => 'About this space & orbits game',
        'aboutText' => 'This free physics game covers the order of the planets from the Sun, and facts about each one — from tiny, scorching Mercury to giant, ringed Saturn. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const ORDER = {
                'Mercury': 1,
                'Venus': 2,
                'Earth': 3,
                'Mars': 4,
                'Jupiter': 5,
                'Saturn': 6,
                'Uranus': 7,
                'Neptune': 8,
            };
            const PLANET_NAMES = Object.keys(ORDER);

            const FACTS = {
                'Mercury': 'The smallest planet, and the closest to the Sun.',
                'Venus': 'The hottest planet, with a thick atmosphere that traps heat.',
                'Earth': 'The only planet known to support life, with liquid water on its surface.',
                'Mars': "Known as the 'Red Planet' because of rusty iron oxide on its surface.",
                'Jupiter': 'The largest planet in the solar system, a gas giant with a huge storm called the Great Red Spot.',
                'Saturn': 'Famous for its wide, bright rings made of ice and rock.',
            };
            const FACT_PLANETS = Object.keys(FACTS);

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
                storageKey: 'spaceOrbitsGame.settings',
                types: ['order', 'facts'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'order') {
                        const planet = PLANET_NAMES[randInt(0, PLANET_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: String(ORDER[planet]),
                            questionText: 'What position is ' + planet + ' from the Sun?',
                        };
                    }
                    const planet = FACT_PLANETS[randInt(0, FACT_PLANETS.length - 1)];
                    return {
                        category: type,
                        fact: FACTS[planet],
                        correctText: planet,
                        questionText: 'Which planet is this: ' + FACTS[planet].toLowerCase() + '?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'order') {
                        const numbers = ['1', '2', '3', '4', '5', '6', '7', '8'];
                        const distractors = pickOthers(numbers, q.correctText, 3);
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(FACT_PLANETS, q.correctText, 3);
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'order') {
                        return 'My Very Easy Method Just Speeds Up Naming: Mercury, Venus, Earth, Mars, Jupiter, Saturn, Uranus, Neptune.';
                    }
                    return 'Think about size, heat, rings, colour, or which one we live on.';
                },

                explanationFor: function(q) {
                    if (q.category === 'facts') return q.correctText + ': ' + q.fact;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a space and orbits superstar!",
            });
        })();
    </script>
@endpush
