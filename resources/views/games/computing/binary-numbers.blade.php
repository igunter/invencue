@extends('layouts.app')

@section('meta_title', 'Binary Numbers — Computing Game for Kids')
@section('meta_blurb', 'A free computing game — convert small binary numbers to denary and back, up to 8 bits.')
@section('meta_words', 'binary numbers game, binary to denary quiz, ks3 computing game, binary conversion for kids')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-123',
        'title' => 'Binary Numbers',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Binary numbers'],
        ],
        'aboutTitle' => 'About this binary numbers game',
        'aboutText' => 'This free computing game tests simple binary and denary conversions using small numbers, up to 8 bits, and key facts about how computers represent numbers using only 0s and 1s.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What is binary 0001 in denary (normal numbers)?", a: "1" },
                { q: "What is binary 0010 in denary?", a: "2" },
                { q: "What is binary 0100 in denary?", a: "4" },
                { q: "What is binary 1000 in denary?", a: "8" },
                { q: "What is binary 0101 in denary?", a: "5" },
                { q: "What is binary 0011 in denary?", a: "3" },
                { q: "What is binary 1010 in denary?", a: "10" },
                { q: "What is binary 1111 in denary?", a: "15" },
                { q: "What is denary 6 written in binary (using 4 bits)?", a: "0110" },
                { q: "What is denary 9 written in binary (using 4 bits)?", a: "1001" },
                { q: "How many different values can one single bit hold?", a: "2 (0 or 1)" },
                { q: "What number system uses only the digits 0 and 1?", a: "Binary" },
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
                storageKey: 'binaryNumbersGame.settings',
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
                    return 'Remember each binary place value doubles: 1, 2, 4, 8 from right to left.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a binary numbers superstar!",
            });
        })();
    </script>
@endpush
