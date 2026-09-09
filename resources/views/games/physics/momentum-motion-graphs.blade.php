@extends('layouts.app')

@section('meta_title', 'Momentum & Motion Graphs — GCSE Physics Game')
@section('meta_blurb', 'A free GCSE physics game covering momentum calculations (p = mv) and how to read distance-time and velocity-time graphs.')
@section('meta_words', 'momentum game, motion graphs game, gcse physics game, distance-time graph, velocity-time graph, p=mv')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-graph-up',
        'title' => 'Momentum & Motion Graphs',
        'subtitle' => 'Pick your question types, then test your motion knowledge!',
        'typeToggles' => [
            ['id' => 'momentum', 'label' => 'Momentum calculations'],
            ['id' => 'graphs', 'label' => 'Motion graphs'],
        ],
        'aboutTitle' => 'About this momentum & motion graphs game',
        'aboutText' => 'This free GCSE physics game practises momentum calculations (p = m × v) and what different features of distance-time and velocity-time graphs actually mean. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            // mass=2 paired with velocity=2 would make "add" equal "multiply";
            // excluding 2 from MASSES avoids that combination entirely.
            const MASSES = [5, 10, 20, 50];
            const VELOCITIES = [2, 3, 4, 5, 10];

            const GRAPHS = {
                'A flat horizontal line on a distance-time graph': 'The object is stationary — it is not moving.',
                'A straight diagonal line on a distance-time graph': 'The object is moving at a constant speed.',
                'A curved, steepening line on a distance-time graph': 'The object is accelerating — speeding up.',
                'A flat horizontal line on a velocity-time graph': 'The object is moving at a constant velocity.',
                'The gradient of a velocity-time graph': "Represents the object's acceleration.",
                'The area under a velocity-time graph': 'Represents the distance travelled by the object.',
                'A curved, flattening line on a distance-time graph': 'The object is decelerating — its speed is decreasing.',
                'A line sloping back down on a distance-time graph': 'The object is moving back towards its starting point.',
                'A downward sloping line on a velocity-time graph': 'The object is decelerating, and may eventually stop or reverse direction.',
                'The steepness (gradient) of a distance-time graph': "Represents the object's speed — the steeper the line, the faster it's moving.",
            };
            const GRAPH_NAMES = Object.keys(GRAPHS);

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
                storageKey: 'momentumMotionGraphsGame.settings',
                types: ['momentum', 'graphs'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'graphs') {
                        const scenario = GRAPH_NAMES[randInt(0, GRAPH_NAMES.length - 1)];
                        return {
                            category: type,
                            label: scenario,
                            correctText: GRAPHS[scenario],
                            questionText: "What does '" + scenario + "' show?",
                        };
                    }
                    const mass = MASSES[randInt(0, MASSES.length - 1)];
                    const velocity = VELOCITIES[randInt(0, VELOCITIES.length - 1)];
                    return {
                        category: type,
                        mass: mass,
                        velocity: velocity,
                        correctText: (mass * velocity) + ' kg m/s',
                        questionText: 'A ' + mass + ' kg object moves at ' + velocity + ' m/s. What is its momentum? (p = m × v)',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'graphs') {
                        const distractors = pickOthers(GRAPH_NAMES, q.label, 3).map(function(g) { return GRAPHS[g]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const correct = q.mass * q.velocity;
                    const wrongAdd = (q.mass + q.velocity) + ' kg m/s';
                    const wrongDivide = Math.round((q.mass / q.velocity) * 100) / 100 + ' kg m/s';
                    const wrongDouble = (correct * 2) + ' kg m/s';
                    return shuffle([correct + ' kg m/s', wrongAdd, wrongDivide, wrongDouble]);
                },

                hintFor: function(q) {
                    if (q.category === 'graphs') {
                        return 'On a distance-time graph, slope means speed. On a velocity-time graph, slope means acceleration and the area underneath means distance.';
                    }
                    return 'p = m × v. Multiply the mass (in kg) by the velocity (in m/s).';
                },

                explanationFor: function(q) {
                    if (q.category === 'momentum') return q.mass + ' kg × ' + q.velocity + ' m/s = ' + q.correctText;
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered momentum and motion graphs!",
            });
        })();
    </script>
@endpush
