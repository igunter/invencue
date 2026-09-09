@extends('layouts.app')

@section('meta_title', 'Forces & Motion — Kids Physics Game')
@section('meta_blurb', 'A free physics game for kids — calculate speed from distance and time, and learn about velocity, acceleration and distance.')
@section('meta_words', 'forces and motion game, kids physics game, speed distance time, velocity, acceleration')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-arrow-right',
        'title' => 'Forces & Motion',
        'subtitle' => 'Pick your question types, then test your motion knowledge!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'calc', 'label' => 'Speed calculations'],
            ['id' => 'terms', 'label' => 'Key terms'],
        ],
        'aboutTitle' => 'About this forces & motion game',
        'aboutText' => 'This free physics game practises calculating speed from distance and time, plus the difference between speed, velocity, acceleration and distance. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const DISTANCES = [10, 20, 40, 50, 100];
            const TIMES = [2, 4, 5, 10];

            const TERMS = {
                'Speed': 'How fast something is moving, calculated as distance ÷ time.',
                'Velocity': 'Speed in a particular direction.',
                'Acceleration': "The rate at which an object's velocity changes.",
                'Distance': 'How far an object has travelled, regardless of direction.',
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
                storageKey: 'forcesMotionGame.settings',
                types: ['calc', 'terms'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'terms') {
                        const term = TERM_NAMES[randInt(0, TERM_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: TERMS[term],
                            questionText: "What is '" + term + "'?",
                        };
                    }
                    const distance = DISTANCES[randInt(0, DISTANCES.length - 1)];
                    const time = TIMES[randInt(0, TIMES.length - 1)];
                    return {
                        category: type,
                        distance: distance,
                        time: time,
                        correctText: (distance / time) + ' m/s',
                        questionText: 'An object travels ' + distance + ' m in ' + time + ' s. What is its speed? (speed = distance ÷ time)',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'terms') {
                        const term = TERM_NAMES.find(function(t) { return TERMS[t] === q.correctText; });
                        const distractors = pickOthers(TERM_NAMES, term, 3).map(function(t) { return TERMS[t]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const correct = q.distance / q.time;
                    const wrongAdd = (q.distance + q.time) + ' m/s';
                    const wrongMultiply = (q.distance * q.time) + ' m/s';
                    const wrongDouble = (correct * 2) + ' m/s';
                    return shuffle([correct + ' m/s', wrongAdd, wrongMultiply, wrongDouble]);
                },

                hintFor: function(q) {
                    if (q.category === 'terms') {
                        return 'Think about how fast, fast in a direction, change in speed, or just how far.';
                    }
                    return 'Speed = distance ÷ time. Divide the distance by the time given.';
                },

                explanationFor: function(q) {
                    if (q.category === 'calc') return q.distance + ' m ÷ ' + q.time + ' s = ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You've mastered forces and motion!",
            });
        })();
    </script>
@endpush
