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
            };
            const BOUNDARY_NAMES = Object.keys(BOUNDARIES);

            const TERMS = {
                'Tectonic plate': "A large section of the Earth's crust that moves slowly over the mantle.",
                'Earthquake': 'A sudden release of energy at a plate boundary, causing the ground to shake.',
                'Seismologist': 'A scientist who studies earthquakes.',
                'Magnitude': 'A measurement of the size, or energy released, of an earthquake.',
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
