@extends('layouts.app')

@section('meta_title', 'Body Language Basics — Psychology Game for Kids')
@section('meta_blurb', 'A free psychology game for kids — work out what a facial expression or posture might show.')
@section('meta_words', 'body language game for kids, reading facial expressions, non-verbal communication, psychology game for kids')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-person-arms-up',
        'title' => 'Body Language Basics',
        'subtitle' => 'Read the clue, then work out what it shows!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Body language'],
        ],
        'aboutTitle' => 'About this body language basics game',
        'aboutText' => 'This free game helps young kids practise reading facial expressions and body posture, spotting what someone might be feeling from the way they look and move.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "Someone is smiling and their eyes are crinkled up. What are they probably feeling?", a: "Happy" },
                { q: "Someone has their arms crossed and is frowning. What might they be feeling?", a: "Annoyed" },
                { q: "Someone is standing tall with their chin up and shoulders back. What might this show?", a: "Confidence" },
                { q: "Someone's shoulders are slumped and they're looking at the floor. What might they be feeling?", a: "Sad" },
                { q: "Someone is fidgeting and won't make eye contact. What might they be feeling?", a: "Nervous" },
                { q: "Someone has wide open eyes and their mouth is open. What might they be feeling?", a: "Surprised" },
                { q: "Someone is clenching their fists with a red face. What might they be feeling?", a: "Angry" },
                { q: "Someone is yawning and rubbing their eyes. What are they probably feeling?", a: "Tired" },
                { q: "Someone is jumping up and down waving their arms. What might they be feeling?", a: "Excited" },
                { q: "Someone is hugging themselves and shivering slightly. What might they be feeling?", a: "Cold or scared" },
                { q: "Someone leans in and nods while you're talking. What does this usually show?", a: "They're listening" },
                { q: "Someone is backing away with their hands up. What might they be feeling?", a: "Scared" },
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
                storageKey: 'bodyLanguageBasicsGame.settings',
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
                    return 'Look at the face, the posture, and what the body is doing.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're great at reading body language!",
            });
        })();
    </script>
@endpush
