@extends('layouts.app')

@section('meta_title', 'Universe Scale — GCSE Earth Science Game')
@section('meta_blurb', 'A free GCSE earth science game covering the relative sizes and distances of the Moon, planets, stars, galaxies and the universe.')
@section('meta_words', 'universe scale game, gcse earth science game, light-year, astronomical unit, galaxy, solar system, space revision')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-arrows-expand',
        'title' => 'Universe Scale',
        'subtitle' => 'Pick your question types, then test your sense of scale!',
        'typeToggles' => [
            ['id' => 'scale', 'label' => 'Scale of the universe'],
            ['id' => 'terms', 'label' => 'Key terms'],
        ],
        'aboutTitle' => 'About this universe scale game',
        'aboutText' => 'This free GCSE earth science game covers how the Moon, Earth, Sun, Solar System, galaxies and the universe compare in size and distance, plus vocabulary like the astronomical unit and light-year used to measure them. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const SCALE = {
                'The Moon': "Roughly a quarter of the Earth's diameter; orbits the Earth at about 384,000 km away.",
                'The Earth': 'One of eight planets orbiting the Sun; about 150 million km (1 AU) from it.',
                'The Sun': 'A star about 1.3 million times the volume of the Earth; the centre of our Solar System.',
                'The Solar System': 'The Sun and everything that orbits it — planets, moons, asteroids and comets.',
                'The Milky Way': 'Our home galaxy, containing over 100 billion stars, including our Sun.',
                'A galaxy': 'A huge collection of billions of stars, gas and dust, held together by gravity.',
                'The universe': 'Everything that exists — all galaxies, stars, planets, and space itself.',
                'A star': 'A huge ball of burning gas that produces its own light and heat through nuclear fusion.',
                'A planet': 'A large object that orbits a star, is rounded by its own gravity, and has cleared its orbit of other debris.',
                'A cluster of galaxies': 'A group of galaxies held together by gravity, among the largest structures in the universe.',
            };
            const SCALE_NAMES = Object.keys(SCALE);

            const TERMS = {
                'Astronomical unit (AU)': 'The average distance from the Earth to the Sun, about 150 million km — used to measure distances within the Solar System.',
                'Light-year': 'The distance light travels in one year, about 9.46 trillion km — used to measure distances between stars and galaxies.',
                'Orbit': 'The curved path an object takes around another object because of gravity.',
                'Telescope': 'An instrument used to observe distant objects in space by collecting light or other radiation.',
                'Gravity': 'The force of attraction between objects with mass, which holds planets in orbit and shapes the universe.',
                'Parsec': 'Another unit astronomers use to measure vast distances in space, based on parallax.',
                'Redshift': 'The stretching of light from distant objects moving away from us, used as evidence that the universe is expanding.',
                'Big Bang': 'The event around 13.8 billion years ago when the universe began expanding from an extremely hot, dense point.',
                'Satellite': 'An object that orbits a larger object in space, either natural, like a moon, or artificial, like the ISS.',
                'Constellation': 'A recognisable pattern of stars in the sky, named after objects, animals or characters.',
            };
            const TERM_NAMES = Object.keys(TERMS);

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
                storageKey: 'universeScaleGame.settings',
                types: ['scale', 'terms'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'scale') {
                        const object = SCALE_NAMES[randInt(0, SCALE_NAMES.length - 1)];
                        return {
                            category: type,
                            label: object,
                            correctText: SCALE[object],
                            questionText: "Which of these best describes '" + object + "'?",
                        };
                    }
                    const term = TERM_NAMES[randInt(0, TERM_NAMES.length - 1)];
                    return {
                        category: type,
                        correctText: TERMS[term],
                        questionText: "What does '" + term + "' mean?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'scale') {
                        const distractors = pickOthers(SCALE_NAMES, q.label, 3).map(function(s) { return SCALE[s]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const term = TERM_NAMES.find(function(t) { return TERMS[t] === q.correctText; });
                    const distractors = pickOthers(TERM_NAMES, term, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'scale') {
                        return 'Order of scale, smallest to largest: Moon, Earth, Sun, Solar System, galaxy, universe.';
                    }
                    return 'Think about whether this is a unit for measuring distance, a path caused by gravity, or an instrument.';
                },

                explanationFor: function(q) {
                    if (q.category === 'scale') return q.label + ': ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You've mastered the scale of the universe!",
            });
        })();
    </script>
@endpush
