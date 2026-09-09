@extends('layouts.app')

@section('meta_title', 'Fair Test Spotter — Kids Science Game')
@section('meta_blurb', 'A free science game for young kids — spot what makes a simple experiment fair, or what has gone wrong.')
@section('meta_words', 'fair test game, kids science game, fair testing, keeping things the same, working scientifically for kids')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-question-circle',
        'title' => 'Fair Test Spotter',
        'subtitle' => 'Pick your question types, then spot the fair test!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'isFair', 'label' => 'Fair or unfair?'],
            ['id' => 'whyUnfair', 'label' => 'What went wrong?'],
        ],
        'aboutTitle' => 'About this fair test spotter game',
        'aboutText' => 'This free science game helps young kids spot whether a simple experiment is a fair test, and explain what has gone wrong when it is not — such as changing more than one thing at a time. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TESTS = {
                'Growing two plants in the same pot size, soil and light, but giving one more water': 'Fair',
                'Testing which paper towel soaks up the most water, using the same amount of water each time': 'Fair',
                'Racing two toy cars down the same ramp from the same height': 'Fair',
                'Timing how fast two different ice cubes melt, in the same room, at the same time': 'Fair',
                'Growing one plant in sun and warmth, and another in shade and cold': 'Unfair',
                'Testing which ball bounces highest by dropping them from different heights': 'Unfair',
                'Comparing how sweet two drinks taste using different sized cups': 'Unfair',
                'Testing how fast two cars go by rolling one down a ramp and pushing the other': 'Unfair',
            };
            const TEST_NAMES = Object.keys(TESTS);

            const REASONS = {
                'Growing one plant in sun and warmth, and another in shade and cold': 'Two things were changed at once (light and temperature), so you cannot tell which one caused the difference.',
                'Testing which ball bounces highest by dropping them from different heights': 'The drop height was not kept the same, so it is not a fair comparison.',
                'Comparing how sweet two drinks taste using different sized cups': 'The cup size was not kept the same, which could affect the comparison.',
                'Testing how fast two cars go by rolling one down a ramp and pushing the other': 'The cars were not started in the same way, so the test is not fair.',
            };
            const REASON_NAMES = Object.keys(REASONS);

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
                storageKey: 'fairTestSpotterGame.settings',
                types: ['isFair', 'whyUnfair'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 25,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'isFair') {
                        const test = TEST_NAMES[randInt(0, TEST_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: TESTS[test],
                            questionText: test + ' — is this a fair test?',
                        };
                    }
                    const test = REASON_NAMES[randInt(0, REASON_NAMES.length - 1)];
                    return {
                        category: type,
                        label: test,
                        correctText: REASONS[test],
                        questionText: test + ' — what makes this unfair?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'isFair') {
                        return shuffle(['Fair', 'Unfair']);
                    }
                    const distractors = pickOthers(REASON_NAMES, q.label, 3).map(function(r) { return REASONS[r]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'isFair') {
                        return 'A fair test only changes one thing at a time and keeps everything else the same.';
                    }
                    return 'Look for something that was different between the two sides that should have been kept the same.';
                },

                explanationFor: function(q) {
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a fair test superstar!",
            });
        })();
    </script>
@endpush
