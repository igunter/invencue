@extends('layouts.app')

@section('meta_title', 'Rhythm & Time Signatures — Music Game for Kids')
@section('meta_blurb', 'A free music game — learn simple time signature facts like 4/4 and 3/4, and how to count beats in a bar.')
@section('meta_words', 'time signatures game, rhythm quiz, counting beats, 4/4 3/4 6/8, ks3 music game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-clock-history',
        'title' => 'Rhythm & Time Signatures',
        'subtitle' => 'Pick your question types, then test your rhythm knowledge!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'meaning', 'label' => 'Time signatures'],
            ['id' => 'counting', 'label' => 'Counting beats'],
        ],
        'aboutTitle' => 'About this rhythm & time signatures game',
        'aboutText' => 'This free music game covers common time signatures such as 4/4, 3/4 and 6/8, and how to count beats in a bar. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TIME_SIGS = {
                '4/4': 'Four beats in a bar, each a crotchet beat — also called common time.',
                '3/4': 'Three beats in a bar, each a crotchet beat — often used for waltzes.',
                '2/4': 'Two beats in a bar, each a crotchet beat — a marching feel.',
                '6/8': 'Six quaver beats in a bar, usually felt in two groups of three.',
                '2/2': 'Two minim beats in a bar — also called cut time.',
                '3/8': 'Three quaver beats in a bar.',
                '5/4': 'Five crotchet beats in a bar, an unusual, irregular time signature.',
                '9/8': 'Nine quaver beats in a bar, usually felt in three groups of three.',
                '12/8': 'Twelve quaver beats in a bar, usually felt in four groups of three.',
                '7/8': 'Seven quaver beats in a bar, an irregular time signature.',
            };
            const TIME_SIG_NAMES = Object.keys(TIME_SIGS);

            const COUNTING = [
                { q: "In a time signature, what does the top number tell you?", a: "How many beats are in each bar" },
                { q: "In a time signature, what does the bottom number tell you?", a: "What type of note counts as one beat" },
                { q: "In 4/4 time, what note value gets one beat?", a: "A crotchet" },
                { q: "In 6/8 time, what note value gets one beat?", a: "A quaver" },
                { q: "What do we call time signatures like 6/8, 9/8 and 12/8, where beats split into threes?", a: "Compound time" },
                { q: "What do we call time signatures like 2/4, 3/4 and 4/4, where beats split into twos?", a: "Simple time" },
                { q: "What do we call time signatures with an unusual number of beats, like 5/4 or 7/8?", a: "Irregular time" },
                { q: "What symbol is sometimes used instead of writing 4/4?", a: "The letter C" },
                { q: "What is the term for the strongest, most emphasised beat in a bar?", a: "The downbeat" },
                { q: "If a bar in 3/4 time has three crotchet beats, how many quavers could fit in that same bar?", a: "Six quavers" },
            ];

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
                storageKey: 'rhythmTimeSignaturesGame.settings',
                types: ['meaning', 'counting'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'meaning') {
                        const sig = TIME_SIG_NAMES[randInt(0, TIME_SIG_NAMES.length - 1)];
                        return {
                            category: type,
                            label: sig,
                            correctText: TIME_SIGS[sig],
                            questionText: "What does the time signature " + sig + " mean?",
                        };
                    }
                    const item = COUNTING[randInt(0, COUNTING.length - 1)];
                    return { category: type, correctText: item.a, questionText: item.q };
                },

                buildChoices: function(q) {
                    if (q.category === 'meaning') {
                        const distractors = pickOthers(TIME_SIG_NAMES, q.label, 3).map(function(t) { return TIME_SIGS[t]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = shuffle(
                        COUNTING.map(function(f) { return f.a; }).filter(function(a) { return a !== q.correctText; })
                    ).slice(0, 3);
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'meaning') {
                        return 'The top number counts the beats; the bottom number says what kind of note is one beat.';
                    }
                    return 'Think about how beats are grouped and counted in a bar.';
                },

                explanationFor: function(q) {
                    if (q.category === 'meaning') return q.label + ': ' + q.correctText;
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a rhythm and time signatures superstar!",
            });
        })();
    </script>
@endpush
