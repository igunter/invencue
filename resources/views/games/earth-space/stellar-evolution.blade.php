@extends('layouts.app')

@section('meta_title', 'Stellar Evolution — GCSE Earth Science Game')
@section('meta_blurb', 'A free GCSE earth science game covering the life cycle of stars — from nebula to white dwarf, neutron star or black hole.')
@section('meta_words', 'stellar evolution game, life cycle of a star, gcse earth science game, red giant, supernova, black hole, nuclear fusion')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-star-fill',
        'title' => 'Stellar Evolution',
        'subtitle' => 'Pick your question types, then test your knowledge of stars!',
        'typeToggles' => [
            ['id' => 'lifecycle', 'label' => 'Life cycle of a star'],
            ['id' => 'terms', 'label' => 'Key terms'],
        ],
        'aboutTitle' => 'About this stellar evolution game',
        'aboutText' => 'This free GCSE earth science game covers every stage of a star\'s life cycle — nebula, main sequence, red giant or supergiant, white dwarf, supernova, neutron star and black hole — plus the vocabulary behind how stars shine. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const LIFECYCLE = {
                'Nebula': 'A cloud of dust and gas that collapses under gravity to form a protostar.',
                'Main sequence star': 'A stable star, like our Sun, fusing hydrogen into helium in its core for billions of years.',
                'Red giant': 'A large, cooler star formed when an average-mass star runs low on hydrogen and expands.',
                'White dwarf': 'The small, dense core left behind after an average-mass star sheds its outer layers.',
                'Red supergiant': 'A huge, short-lived star formed when a massive star runs low on hydrogen and expands dramatically.',
                'Supernova': 'A massive, explosive death of a large star, briefly outshining an entire galaxy.',
                'Neutron star': 'An extremely dense collapsed core left behind after a supernova, made almost entirely of neutrons.',
                'Black hole': 'An extremely dense object with gravity so strong that not even light can escape, formed from the largest stars.',
                'Protostar': 'A dense, hot clump of collapsing gas and dust that has not yet started nuclear fusion — the stage between a nebula and a main sequence star.',
                'Planetary nebula': 'The glowing shell of gas shed by a dying average-mass star as it loses its outer layers, leaving behind a white dwarf.',
            };
            const LIFECYCLE_NAMES = Object.keys(LIFECYCLE);

            const TERMS = {
                'Nuclear fusion': 'The process of joining light atomic nuclei together to form heavier ones, releasing huge amounts of energy — what powers stars.',
                'Protostar': 'A very young star still forming, before nuclear fusion has started in its core.',
                'Luminosity': 'A measure of how much light energy a star gives out.',
                'Core': 'The central region of a star, where nuclear fusion happens.',
                'Gravity': 'The force that pulls matter together, causing gas clouds to collapse and stars to form.',
                'Mass': 'The amount of matter in a star, which determines how it will evolve and eventually die.',
                'Hertzsprung-Russell diagram': 'A chart that plots stars by temperature and brightness, used to classify and study them.',
                'Solar mass': "A unit used to measure the mass of stars, based on the mass of our Sun.",
                'Binary star': 'A system of two stars that orbit each other.',
                'Pulsar': 'A rapidly spinning neutron star that emits regular beams of radiation, detected as pulses from Earth.',
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
                storageKey: 'stellarEvolutionGame.settings',
                types: ['lifecycle', 'terms'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'lifecycle') {
                        const stage = LIFECYCLE_NAMES[randInt(0, LIFECYCLE_NAMES.length - 1)];
                        return {
                            category: type,
                            label: stage,
                            correctText: LIFECYCLE[stage],
                            questionText: "What is a '" + stage + "'?",
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
                    if (q.category === 'lifecycle') {
                        const distractors = pickOthers(LIFECYCLE_NAMES, q.label, 3).map(function(s) { return LIFECYCLE[s]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const term = TERM_NAMES.find(function(t) { return TERMS[t] === q.correctText; });
                    const distractors = pickOthers(TERM_NAMES, term, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'lifecycle') {
                        return 'A star\'s mass decides its fate: average-mass stars end as white dwarfs; the most massive end as neutron stars or black holes after a supernova.';
                    }
                    return 'Think about whether this is the energy-producing reaction itself, a young or dim star, or a measurement.';
                },

                explanationFor: function(q) {
                    if (q.category === 'lifecycle') return q.label + ': ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You've mastered stellar evolution!",
            });
        })();
    </script>
@endpush
