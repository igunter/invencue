@extends('layouts.app')

@section('meta_title', 'Keyboard & Typing Basics — Computing Game for Kids')
@section('meta_blurb', 'A free computing game for kids — learn what different keyboard keys do, like the space bar, enter and shift.')
@section('meta_words', 'keyboard basics game, typing game for kids, keyboard keys quiz, ks1 ks2 computing')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-keyboard',
        'title' => 'Keyboard & Typing Basics',
        'subtitle' => 'Read the clue, then work out the key!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Keyboard keys'],
        ],
        'aboutTitle' => 'About this keyboard & typing basics game',
        'aboutText' => 'This free computing game helps young kids learn what different keys on a keyboard do — from the space bar and enter key to shift, backspace and the arrow keys.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "Which key do you press to add a space between words?", a: "The space bar" },
                { q: "Which key moves your writing down to a new line?", a: "The Enter (Return) key" },
                { q: "Which key do you hold down to type a capital letter?", a: "The Shift key" },
                { q: "Which key deletes the letter just before the cursor?", a: "The Backspace key" },
                { q: "Which key moves the cursor forward without typing anything, often used to jump between boxes?", a: "The Tab key" },
                { q: "Which key is used with other keys to copy, like Ctrl + C?", a: "The Ctrl (Control) key" },
                { q: "Which key clears a menu or cancels some actions on screen?", a: "The Escape (Esc) key" },
                { q: "Which keys have arrows on them and let you move the cursor around the screen?", a: "The arrow keys" },
                { q: "Which key turns all your typing into capital letters until you press it again?", a: "Caps Lock" },
                { q: "Which part of the keyboard has the numbers 0-9 laid out like a calculator?", a: "The number pad" },
                { q: "What is the blinking line on the screen that shows where your next letter will appear called?", a: "The cursor" },
                { q: "Which key do you press to delete the letter in front of (after) the cursor?", a: "The Delete key" },
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
                storageKey: 'keyboardTypingBasicsGame.settings',
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
                    return 'Picture a keyboard and think about what happens when you press that key.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a keyboard superstar!",
            });
        })();
    </script>
@endpush
