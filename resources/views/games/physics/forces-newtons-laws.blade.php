@extends('layouts.app')

@section('meta_title', "Forces & Newton's Laws — GCSE Physics Game")
@section('meta_blurb', "A free GCSE physics game covering Newton's three laws of motion, plus F = ma force calculations.")
@section('meta_words', "forces game, newton's laws game, gcse physics game, F=ma, resultant force, terminal velocity, motion revision")

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-calculator',
        'title' => "Forces & Newton's Laws",
        'subtitle' => 'Pick your question types, then put the laws of motion to the test!',
        'typeToggles' => [
            ['id' => 'calc', 'label' => 'F = ma calculations'],
            ['id' => 'laws', 'label' => "Newton's laws & terms"],
        ],
        'aboutTitle' => "About this Forces & Newton's Laws game",
        'aboutText' => "This free GCSE physics game covers Newton's three laws of motion, resultant force and terminal velocity, plus randomised F = ma calculations. Choose which question types to include, set your question count and time limit, then see how many you can get right.",
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            // mass=2 and accel=1 are deliberately excluded: they make the "add
            // instead of multiply" or "divide instead of multiply" distractors
            // below collide with the correct answer (e.g. 2+2 = 2×2).
            const MASSES = [4, 5, 8, 10, 20, 50];
            const ACCELERATIONS = [2, 3, 4, 5, 10];

            const LAWS = {
                "Newton's First Law": 'An object stays at rest, or moving at a constant velocity, unless a resultant force acts on it.',
                "Newton's Second Law": "The acceleration of an object is proportional to the resultant force on it and inversely proportional to its mass (F = ma).",
                "Newton's Third Law": 'When two objects interact, they exert equal and opposite forces on each other.',
                'Resultant force': 'The single force that would have the same effect as all the forces acting on an object combined.',
                'Terminal velocity': 'The maximum, constant speed reached when the resultant force on a falling object becomes zero.',
                'Inertia': 'The tendency of an object to resist a change in its motion; more mass means more inertia.',
                'Weight': "The force of gravity acting on an object's mass, measured in newtons.",
                'Friction': 'A force that opposes motion between two surfaces in contact, or between an object and a fluid.',
                'Newton (N)': 'The SI unit of force, defined as the force needed to accelerate a mass of 1 kg by 1 m/s².',
                'Free body diagram': 'A diagram showing all the forces acting on a single object, drawn as arrows.',
            };
            const LAW_NAMES = Object.keys(LAWS);

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
                storageKey: 'forcesNewtonsLawsGame.settings',
                types: ['calc', 'laws'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'laws') {
                        const law = LAW_NAMES[randInt(0, LAW_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: LAWS[law],
                            questionText: "What does '" + law + "' state?",
                        };
                    }
                    const mass = MASSES[randInt(0, MASSES.length - 1)];
                    const accel = ACCELERATIONS[randInt(0, ACCELERATIONS.length - 1)];
                    const force = mass * accel;
                    return {
                        category: type,
                        mass: mass,
                        accel: accel,
                        correctText: force + ' N',
                        questionText: 'A ' + mass + ' kg object accelerates at ' + accel + ' m/s². What resultant force is needed? (F = m × a)',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'laws') {
                        const law = LAW_NAMES.find(function(l) { return LAWS[l] === q.correctText; });
                        const distractors = pickOthers(LAW_NAMES, law, 3).map(function(l) { return LAWS[l]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const wrongAdd = (q.mass + q.accel) + ' N';
                    const wrongDivide = Math.round((q.mass / q.accel) * 100) / 100 + ' N';
                    const wrongDouble = (q.mass * q.accel * 2) + ' N';
                    return shuffle([q.correctText, wrongAdd, wrongDivide, wrongDouble]);
                },

                hintFor: function(q) {
                    if (q.category === 'laws') {
                        return "First law is about no change without a force. Second law is F = ma. Third law is about equal and opposite pairs.";
                    }
                    return 'Force = mass × acceleration. Multiply the two numbers given in the question.';
                },

                explanationFor: function(q) {
                    if (q.category === 'calc') return q.mass + ' kg × ' + q.accel + ' m/s² = ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You've mastered forces and Newton's laws!",
            });
        })();
    </script>
@endpush
