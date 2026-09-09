@extends('layouts.app')

@section('meta_title', 'Push & Pull — Kids Physics Game')
@section('meta_blurb', 'A free physics game for young kids — decide whether an action is a push or a pull, and learn about forces, gravity and friction.')
@section('meta_words', 'push and pull game, kids physics game, forces for kids, gravity, friction, learn about forces')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-arrow-left-right',
        'title' => 'Push & Pull',
        'subtitle' => 'Pick your question types, then spot that force!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'identify', 'label' => 'Push or pull?'],
            ['id' => 'facts', 'label' => 'Force facts'],
        ],
        'aboutTitle' => 'About this push & pull game',
        'aboutText' => 'This free physics game helps young kids decide whether an everyday action is a push or a pull, and learn about forces, gravity and friction. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const ACTIONS = {
                'Opening a door towards you': 'Pull',
                'Kicking a ball': 'Push',
                'Pushing a shopping trolley': 'Push',
                'Pulling a rope in tug of war': 'Pull',
                'Closing a drawer': 'Push',
                'Pulling a wagon along': 'Pull',
                'Throwing a ball': 'Push',
                'Dragging a sledge': 'Pull',
                'Pushing a swing to make it go': 'Push',
                'Pulling a zip up': 'Pull',
            };
            const ACTION_NAMES = Object.keys(ACTIONS);

            const FACTS = {
                'Force': 'A push or a pull that can make an object start, stop, speed up, slow down, or change direction.',
                'Gravity': 'A pulling force that pulls everything down towards the Earth.',
                'Friction': 'A force that slows things down when two surfaces rub together.',
                'The strength of a force': 'The harder you push or pull, the bigger the force — and usually the bigger the effect.',
                'Air resistance': 'A force that pushes against things moving through the air, slowing them down.',
                'Water resistance': 'A force that pushes against things moving through water, slowing them down.',
                'Magnetism': 'A pulling or pushing force between magnets, or between a magnet and certain metals.',
                'Upthrust': 'A force from water (or another fluid) that pushes an object upwards, helping it float.',
                'Balanced forces': 'When forces acting on an object are equal and opposite, so it does not change its speed or direction.',
                'Unbalanced forces': "When forces acting on an object aren't equal, causing it to speed up, slow down, or change direction.",
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
                storageKey: 'pushPullGame.settings',
                types: ['identify', 'facts'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'identify') {
                        const action = ACTION_NAMES[randInt(0, ACTION_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: ACTIONS[action],
                            questionText: action + ' — is this a push or a pull?',
                        };
                    }
                    const fact = FACT_NAMES[randInt(0, FACT_NAMES.length - 1)];
                    return {
                        category: type,
                        label: fact,
                        correctText: FACTS[fact],
                        questionText: "What is '" + fact + "'?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'identify') {
                        return shuffle(['Push', 'Pull']);
                    }
                    const distractors = pickOthers(FACT_NAMES, q.label, 3).map(function(f) { return FACTS[f]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'identify') {
                        return 'Are you moving something towards you, or away from you?';
                    }
                    return 'Think about pushes and pulls in general, the pull from the Earth, or what happens when things rub together.';
                },

                explanationFor: function(q) {
                    if (q.category === 'facts') return q.label + ': ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a push and pull superstar!",
            });
        })();
    </script>
@endpush
