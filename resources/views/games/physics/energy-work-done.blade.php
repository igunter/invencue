@extends('layouts.app')

@section('meta_title', 'Energy & Work Done — GCSE Physics Game')
@section('meta_blurb', 'A free GCSE physics numeracy game — work done (W = Fd) and gravitational potential energy (GPE = mgh) calculations.')
@section('meta_words', 'energy calculations game, work done game, gcse physics game, gravitational potential energy, W=Fd, GPE=mgh')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-arrow-up-circle',
        'title' => 'Energy & Work Done',
        'subtitle' => 'Pick your question types, then crunch some energy numbers!',
        'typeToggles' => [
            ['id' => 'work', 'label' => 'Work done (W = Fd)'],
            ['id' => 'gpe', 'label' => 'Potential energy (GPE = mgh)'],
        ],
        'aboutTitle' => 'About this energy & work done game',
        'aboutText' => 'This free GCSE physics game practises work done (W = force × distance) and gravitational potential energy (GPE = mass × gravity × height, using g = 10 N/kg) with randomised numbers each time. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FORCES = [10, 20, 30, 50, 100];
            const DISTANCES = [2, 3, 4, 5, 10];
            const MASSES = [2, 5, 10, 20, 50];
            // 1 and 10 are excluded from HEIGHTS: with g = 10, a height of 1
            // would make the "forgot height" distractor (m × g) equal the
            // correct answer, and a height of 10 would make the "forgot g"
            // distractor (m × h) equal the "forgot height" one.
            const HEIGHTS = [2, 3, 5];
            const G = 10;

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

            window.ScienceQuiz.run({
                storageKey: 'energyWorkDoneGame.settings',
                types: ['work', 'gpe'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'work') {
                        const force = FORCES[randInt(0, FORCES.length - 1)];
                        const distance = DISTANCES[randInt(0, DISTANCES.length - 1)];
                        return {
                            category: type,
                            force: force,
                            distance: distance,
                            correctText: (force * distance) + ' J',
                            questionText: 'A force of ' + force + ' N moves an object ' + distance + ' m. How much work is done? (W = F × d)',
                        };
                    }
                    const mass = MASSES[randInt(0, MASSES.length - 1)];
                    const height = HEIGHTS[randInt(0, HEIGHTS.length - 1)];
                    return {
                        category: type,
                        mass: mass,
                        height: height,
                        correctText: (mass * G * height) + ' J',
                        questionText: 'What is the gravitational potential energy of a ' + mass + ' kg object raised ' + height + ' m? (use g = 10 N/kg)',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'work') {
                        const correct = q.force * q.distance;
                        const wrongAdd = (q.force + q.distance) + ' J';
                        const wrongDivide = Math.round((q.force / q.distance) * 100) / 100 + ' J';
                        const wrongDouble = (correct * 2) + ' J';
                        return shuffle([correct + ' J', wrongAdd, wrongDivide, wrongDouble]);
                    }
                    const correct = q.mass * G * q.height;
                    const wrongNoG = (q.mass * q.height) + ' J';
                    const wrongNoH = (q.mass * G) + ' J';
                    const wrongDouble = (correct * 2) + ' J';
                    return shuffle([correct + ' J', wrongNoG, wrongNoH, wrongDouble]);
                },

                hintFor: function(q) {
                    if (q.category === 'work') {
                        return 'W = F × d. Multiply the force (in newtons) by the distance moved (in metres).';
                    }
                    return 'GPE = m × g × h. Multiply the mass, then by g (10 N/kg), then by the height.';
                },

                explanationFor: function(q) {
                    if (q.category === 'work') return q.force + ' N × ' + q.distance + ' m = ' + q.correctText;
                    return q.mass + ' kg × 10 N/kg × ' + q.height + ' m = ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered energy and work done!",
            });
        })();
    </script>
@endpush
