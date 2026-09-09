@extends('layouts.app')

@section('meta_title', 'Computer Parts — Computing Game for Kids')
@section('meta_blurb', 'A free computing game for kids — name the parts of a computer, like the mouse, keyboard, monitor and speakers.')
@section('meta_words', 'computer parts game, kids computing game, monitor keyboard mouse quiz, parts of a computer, ks1 ks2 computing')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-display',
        'title' => 'Computer Parts',
        'subtitle' => 'Read the clue, then work out which part it is!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Computer parts'],
        ],
        'aboutTitle' => 'About this computer parts game',
        'aboutText' => 'This free computing game helps young kids learn the names of the parts that make up a computer — the monitor, keyboard, mouse, speakers and more — and what job each part does.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "Which part of the computer do you look at to see pictures and words?", a: "The monitor (screen)" },
                { q: "Which part do you type letters and numbers on?", a: "The keyboard" },
                { q: "Which part do you click and move to point at things on screen?", a: "The mouse" },
                { q: "Which part lets you hear sounds from the computer?", a: "Speakers" },
                { q: "Which part takes a picture and lets you see yourself on a video call?", a: "The webcam" },
                { q: "Which part stores all your files and programs inside the computer?", a: "The hard drive" },
                { q: "What is the main box that holds most of a desktop computer's parts called?", a: "The computer tower (case)" },
                { q: "Which part do you talk into so people can hear your voice?", a: "The microphone" },
                { q: "Which part prints your work onto paper?", a: "The printer" },
                { q: "What do we call the part that connects a laptop to the internet without wires?", a: "Wi-Fi (wireless connection)" },
                { q: "Which part do you plug a USB stick into?", a: "A USB port" },
                { q: "What is the flat, touch-sensitive pad used to move the pointer on a laptop called?", a: "The trackpad (touchpad)" },
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
                storageKey: 'computerPartsGame.settings',
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
                    return 'Think about what job that part does — showing, typing, clicking, listening or storing.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You know your computer parts inside out!",
            });
        })();
    </script>
@endpush
