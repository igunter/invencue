@extends('layouts.app')

@section('meta_title', 'Feelings & Emotions — Psychology Game for Kids')
@section('meta_blurb', 'A free psychology game for kids — read a short scenario and work out how the person is probably feeling.')
@section('meta_words', 'feelings game for kids, emotions game, emotional literacy, how are you feeling, psychology game for kids')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-emoji-smile',
        'title' => 'Feelings & Emotions',
        'subtitle' => 'Read the clue, then work out how they feel!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Feelings'],
        ],
        'aboutTitle' => 'About this feelings & emotions game',
        'aboutText' => 'This free game helps young kids build emotional literacy by reading short everyday scenarios and working out how the person involved is probably feeling — happy, sad, nervous, angry, excited and more.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "Maya just won first place in the school race. How does she probably feel?", a: "Happy" },
                { q: "Jack's best friend moved to a different school. How might Jack feel?", a: "Sad" },
                { q: "Priya has to give a speech in front of the whole class tomorrow. How might she feel?", a: "Nervous" },
                { q: "Someone took Leo's toy without asking. How might Leo feel?", a: "Angry" },
                { q: "Ben heard a loud bang in the dark and didn't know what it was. How might he feel?", a: "Scared" },
                { q: "Aisha opened a present and found exactly what she wanted. How does she feel?", a: "Excited" },
                { q: "Tom has been rushing to finish his homework and it's due in five minutes. How might he feel?", a: "Stressed" },
                { q: "Zara saw a puppy doing something silly and couldn't stop giggling. How does she feel?", a: "Amused" },
                { q: "Oliver is waiting to open his birthday presents at his party. How might he feel?", a: "Excited" },
                { q: "Freya's little brother broke her favourite toy. How might she feel?", a: "Upset" },
                { q: "Ali just learned to ride his bike without stabilisers for the first time. How does he feel?", a: "Proud" },
                { q: "Grace is at a new school and doesn't know anyone yet. How might she feel?", a: "Lonely" },
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

            window.ScienceQuiz.run({
                storageKey: 'feelingsEmotionsGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const item = FACTS[randInt(0, FACTS.length - 1)];
                    return { category: type, correctText: item.a, questionText: item.q };
                },

                buildChoices: function(q) {
                    const distractors = shuffle(
                        FACTS.map(function(f) { return f.a; }).filter(function(a) { return a !== q.correctText; })
                    ).slice(0, 3);
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return "Think about how you'd feel if this happened to you.";
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a feelings expert!",
            });
        })();
    </script>
@endpush
