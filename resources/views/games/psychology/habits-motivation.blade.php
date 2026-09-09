@extends('layouts.app')

@section('meta_title', 'Habits & Motivation — Psychology Game for Kids')
@section('meta_blurb', 'A free psychology game covering how habits form, and intrinsic vs extrinsic motivation.')
@section('meta_words', 'habits and motivation game, intrinsic extrinsic motivation, how habits form, psychology game for kids')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-graph-up-arrow',
        'title' => 'Habits & Motivation',
        'subtitle' => 'Pick your question types, then test your knowledge!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'habits', 'label' => 'Habits'],
            ['id' => 'motivation', 'label' => 'Motivation'],
        ],
        'aboutTitle' => 'About this habits & motivation game',
        'aboutText' => 'This free game explains how habits form through cues, routines and rewards, and introduces the difference between intrinsic motivation (doing something because you enjoy it) and extrinsic motivation (doing something for a reward).',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const HABITS = {
                'Habit': 'A behaviour you do automatically, often without thinking about it.',
                'Habit loop': 'The cycle of a cue, a routine, and a reward that forms a habit.',
                'Cue': 'A trigger or signal that starts a habit, like a time of day or a feeling.',
                'Routine': 'The behaviour itself that you do as part of a habit.',
                'Reward': 'The positive feeling or result that makes a habit likely to be repeated.',
                'Repetition': 'Doing something over and over, which is key to forming a new habit.',
                'Breaking a habit': 'Stopping an old automatic behaviour, often by changing the cue or routine.',
                'New habit formation': 'Building a new automatic behaviour, usually through consistent repetition.',
                'Trigger': 'Another word for the cue that sets off a habit.',
                'Consistency': 'Doing something regularly, which helps turn a behaviour into a habit.',
            };
            const HABIT_NAMES = Object.keys(HABITS);

            const MOTIVATION = {
                'Motivation': 'The reason or drive behind why we do something.',
                'Intrinsic motivation': 'Doing something because you enjoy it or find it personally rewarding.',
                'Extrinsic motivation': 'Doing something to get an external reward or avoid a punishment.',
                'Reading for fun': 'An example of intrinsic motivation.',
                'Doing chores for pocket money': 'An example of extrinsic motivation.',
                'Playing a sport because you love it': 'An example of intrinsic motivation.',
                'Studying hard to win a prize': 'An example of extrinsic motivation.',
                'Goal': 'Something you are aiming to achieve, which can boost motivation.',
                'Self-motivation': 'Being able to motivate yourself without needing outside encouragement.',
                'Praise': 'Positive words that can act as a motivating reward for some people.',
            };
            const MOTIVATION_NAMES = Object.keys(MOTIVATION);

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
                storageKey: 'habitsMotivationGame.settings',
                types: ['habits', 'motivation'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'habits') {
                        const name = HABIT_NAMES[randInt(0, HABIT_NAMES.length - 1)];
                        return {
                            category: type,
                            label: name,
                            correctText: HABITS[name],
                            questionText: "What does '" + name + "' mean?",
                        };
                    }
                    const name = MOTIVATION_NAMES[randInt(0, MOTIVATION_NAMES.length - 1)];
                    return {
                        category: type,
                        label: name,
                        correctText: MOTIVATION[name],
                        questionText: "What does '" + name + "' mean, or what is it an example of?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'habits') {
                        const distractors = pickOthers(HABIT_NAMES, q.label, 3).map(function(n) { return HABITS[n]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(MOTIVATION_NAMES, q.label, 3).map(function(n) { return MOTIVATION[n]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'habits') {
                        return 'Think about the cycle of cue, routine and reward.';
                    }
                    return 'Think about whether the reason comes from inside you, or from an outside reward.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You understand habits and motivation!",
            });
        })();
    </script>
@endpush
