@extends('layouts.app')

@section('meta_title', 'Binary & Data Representation — GCSE Computer Science Game')
@section('meta_blurb', 'A free GCSE computer science game covering binary, hexadecimal, ASCII and bitmap image representation.')
@section('meta_words', 'binary data representation game, gcse computer science game, hexadecimal ascii quiz, bitmap images')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-file-binary',
        'title' => 'Binary & Data Representation',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Data representation'],
        ],
        'aboutTitle' => 'About this binary & data representation game',
        'aboutText' => 'This free GCSE computer science game covers how computers represent data — binary and hexadecimal numbers, the ASCII character code, and how bitmap images are stored as binary digits.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What number system, using only 0s and 1s, do computers use to represent all data?", a: "Binary" },
                { q: "What number system uses digits 0-9 and letters A-F, often used as a shorthand for binary?", a: "Hexadecimal" },
                { q: "What is the smallest unit of data in a computer, either a 0 or a 1, called?", a: "A bit" },
                { q: "How many bits make up one byte?", a: "8 bits" },
                { q: "What is denary 12 written in binary (using 4 bits)?", a: "1100" },
                { q: "What is binary 1001 in denary?", a: "9" },
                { q: "What is hexadecimal used for in computing, such as in colour codes?", a: "As a shorter way to represent binary numbers" },
                { q: "What standard code assigns a number to each letter, digit and symbol so computers can represent text?", a: "ASCII" },
                { q: "In a simple black and white bitmap image, what does each binary digit usually represent?", a: "Whether a pixel is black (1) or white (0)" },
                { q: "What do we call each tiny square of colour that makes up a digital image?", a: "A pixel" },
                { q: "What term describes the number of bits used to represent the colour of each pixel in an image?", a: "Colour depth" },
                { q: "What happens to an image's file size as its resolution and colour depth increase?", a: "The file size increases" },
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
                storageKey: 'binaryDataRepresentationGame.settings',
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
                    return 'Think about which number system or coding scheme this question is describing.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You've mastered binary & data representation!",
            });
        })();
    </script>
@endpush
