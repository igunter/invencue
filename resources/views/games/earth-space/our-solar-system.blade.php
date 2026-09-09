@extends('layouts.app')

@section('meta_title', 'Our Solar System — Kids Earth Science Game')
@section('meta_blurb', 'A free earth science game for young kids — match the planets to their order from the Sun, and learn fun facts about the solar system.')
@section('meta_words', 'solar system game, kids earth science game, planets in order, sun moon earth mars saturn facts')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-globe2',
        'title' => 'Our Solar System',
        'subtitle' => 'Pick your question types, then test your solar system knowledge!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'order', 'label' => 'Order from the Sun'],
            ['id' => 'facts', 'label' => 'Fun facts'],
        ],
        'aboutTitle' => 'About this our solar system game',
        'aboutText' => 'This free earth science game helps young kids learn the order of the planets from the Sun, plus fun facts about the Sun, Moon, Earth, Mars and Saturn. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
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
                'Pluto': 9,
                'Eris': 10,
            };
            const PLANET_NAMES = Object.keys(ORDER);
            const NUMBER_LIST = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'];

            const FACTS = {
                'The Sun': 'A giant star at the centre of our solar system that gives us light and heat.',
                'The Moon': "Earth's only natural satellite — it orbits around our planet.",
                'Earth': 'The planet we live on — the third planet from the Sun.',
                'Mars': "Known as the 'Red Planet'.",
                'Saturn': 'Famous for its beautiful rings made of ice and rock.',
                'Mercury': 'The smallest planet in the solar system, and the closest one to the Sun.',
                'Venus': 'The hottest planet, covered in thick clouds that trap heat.',
                'Jupiter': 'The biggest planet in the solar system, with a giant storm called the Great Red Spot.',
                'Uranus': 'Tipped right over on its side, so it spins very differently to the other planets.',
                'Neptune': 'The furthest planet from the Sun, and one of the windiest places in the solar system.',
            };
            const FACT_NAMES = Object.keys(FACTS);

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
                storageKey: 'ourSolarSystemGame.settings',
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
                            questionText: 'What number planet or dwarf planet from the Sun is ' + planet + '?',
                        };
                    }
                    const term = FACT_NAMES[randInt(0, FACT_NAMES.length - 1)];
                    return {
                        category: type,
                        correctText: term,
                        fact: FACTS[term],
                        questionText: 'Which of these is ' + FACTS[term].toLowerCase() + '?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'order') {
                        const distractors = pickOthers(NUMBER_LIST, q.correctText, 3);
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(FACT_NAMES, q.correctText, 3);
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'order') {
                        return 'Remember: Mercury, Venus, Earth, Mars, Jupiter, Saturn, Uranus, Neptune are the 8 planets in order from the Sun, with the dwarf planets Pluto and Eris further out.';
                    }
                    return 'Think about which one gives light, which one orbits us, which one we live on, or which ones have a nickname or rings.';
                },

                explanationFor: function(q) {
                    if (q.category === 'facts') return q.correctText + ': ' + q.fact;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a solar system superstar!",
            });
        })();
    </script>
@endpush
