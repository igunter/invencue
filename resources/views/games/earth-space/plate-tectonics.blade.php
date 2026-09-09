@extends('layouts.app')

@section('meta_title', 'Plate Tectonics — GCSE Earth Science Game')
@section('meta_blurb', 'A free GCSE earth science game covering tectonic plate boundary types and the earthquakes, volcanoes and mountains they cause.')
@section('meta_words', 'plate tectonics game, gcse earth science game, earthquake, volcano, plate boundary, tectonic plates revision')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-arrows-collapse',
        'title' => 'Plate Tectonics',
        'subtitle' => 'Pick your question types, then test your tectonics knowledge!',
        'typeToggles' => [
            ['id' => 'boundaries', 'label' => 'Plate boundaries'],
            ['id' => 'terms', 'label' => 'Key terms'],
        ],
        'aboutTitle' => 'About this plate tectonics game',
        'aboutText' => 'This free GCSE earth science game covers the four types of tectonic plate boundary — destructive, constructive, conservative and collision — and the earthquakes, volcanoes and mountain ranges each one produces. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const BOUNDARIES = {
                'Destructive (convergent) boundary': 'Two plates move towards each other; one is forced under the other, often causing volcanoes and deep earthquakes.',
                'Constructive (divergent) boundary': 'Two plates move apart; magma rises to fill the gap, forming new crust — common at ocean ridges.',
                'Conservative (transform) boundary': 'Two plates slide past each other; friction builds up and is released as earthquakes, with no crust made or destroyed.',
                'Collision boundary': 'Two continental plates collide and crumple upwards, forming large mountain ranges like the Himalayas.',
                'Ocean-continental destructive boundary': 'A dense oceanic plate is subducted beneath a lighter continental plate, causing explosive volcanoes and deep ocean trenches, such as the Andes.',
                'Ocean-ocean destructive boundary': 'One oceanic plate is subducted beneath another, forming volcanic island arcs and deep ocean trenches, such as Japan.',
                'Mid-ocean ridge': 'An underwater mountain range formed at a constructive boundary where two oceanic plates move apart, such as the Mid-Atlantic Ridge.',
                'San Andreas Fault': 'A famous conservative boundary in California where the Pacific and North American plates slide past each other, causing frequent earthquakes.',
                'The Ring of Fire': 'A horseshoe-shaped zone around the Pacific Ocean with frequent earthquakes and volcanoes, caused by many destructive plate boundaries.',
                'The Himalayas': 'A mountain range formed by the ongoing collision between the Indian and Eurasian continental plates.',
            };
            const BOUNDARY_NAMES = Object.keys(BOUNDARIES);

            const TERMS = {
                'Tectonic plate': "A large section of the Earth's crust that moves slowly over the mantle.",
                'Earthquake': 'A sudden release of energy at a plate boundary, causing the ground to shake.',
                'Seismologist': 'A scientist who studies earthquakes.',
                'Magnitude': 'A measurement of the size, or energy released, of an earthquake.',
                'Mantle': "The semi-molten layer of rock beneath the Earth's crust that tectonic plates slowly move over.",
                'Crust': "The thin, solid outer layer of the Earth, broken up into tectonic plates.",
                'Epicentre': "The point on the Earth's surface directly above where an earthquake starts.",
                'Focus': 'The point underground where an earthquake originates.',
                'Richter scale': 'A scale used to measure the magnitude, or size, of an earthquake.',
                'Tsunami': 'A series of huge ocean waves, often triggered by an underwater earthquake.',
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
                storageKey: 'plateTectonicsGame.settings',
                types: ['boundaries', 'terms'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'boundaries') {
                        const boundary = BOUNDARY_NAMES[randInt(0, BOUNDARY_NAMES.length - 1)];
                        return {
                            category: type,
                            label: boundary,
                            correctText: BOUNDARIES[boundary],
                            questionText: "What happens at a '" + boundary + "'?",
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
                    if (q.category === 'boundaries') {
                        const distractors = pickOthers(BOUNDARY_NAMES, q.label, 3).map(function(b) { return BOUNDARIES[b]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const term = TERM_NAMES.find(function(t) { return TERMS[t] === q.correctText; });
                    const distractors = pickOthers(TERM_NAMES, term, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'boundaries') {
                        return "Think about whether the plates are moving apart, together, sliding past each other, or both being continental (neither sinks).";
                    }
                    return 'Think about whether this is about the moving rock itself, the sudden event it causes, or how that event is studied or measured.';
                },

                explanationFor: function(q) {
                    if (q.category === 'boundaries') return q.label + ': ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You've mastered plate tectonics!",
            });
        })();
    </script>
@endpush
