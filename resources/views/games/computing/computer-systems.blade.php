@extends('layouts.app')

@section('meta_title', 'Computer Systems — GCSE Computer Science Game')
@section('meta_blurb', 'A free GCSE computer science game covering the CPU, RAM, ROM, secondary storage and the fetch-execute cycle.')
@section('meta_words', 'computer systems game, gcse computer science game, CPU RAM ROM quiz, fetch execute cycle, secondary storage')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-motherboard',
        'title' => 'Computer Systems',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Computer systems'],
        ],
        'aboutTitle' => 'About this computer systems game',
        'aboutText' => 'This free GCSE computer science game covers the hardware inside a computer system — the CPU, RAM, ROM and secondary storage — and the fetch-execute cycle the CPU uses to run instructions.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What is the 'brain' of the computer that carries out instructions called?", a: "The CPU (Central Processing Unit)" },
                { q: "What type of memory temporarily stores data and programs currently in use, and is cleared when the power is off?", a: "RAM (Random Access Memory)" },
                { q: "What type of memory permanently stores the instructions needed to start up the computer, and cannot be changed?", a: "ROM (Read Only Memory)" },
                { q: "What term describes storage that keeps data even when the power is off, like a hard drive?", a: "Secondary storage (non-volatile storage)" },
                { q: "What is the repeating process the CPU uses to fetch, decode and carry out instructions called?", a: "The fetch-execute (fetch-decode-execute) cycle" },
                { q: "What part of the CPU fetches the next instruction from memory during the fetch-execute cycle?", a: "The control unit" },
                { q: "What term describes memory that loses its contents when power is removed?", a: "Volatile memory" },
                { q: "What part of the CPU performs calculations and logical operations?", a: "The ALU (Arithmetic Logic Unit)" },
                { q: "What measurement describes how many cycles per second a CPU can perform, often given in GHz?", a: "Clock speed" },
                { q: "What do we call the number of processing units, called cores, a modern CPU can have?", a: "Cores (multi-core processing)" },
                { q: "What type of storage would you use to permanently save a document you'll need next year?", a: "Secondary storage (e.g. an SSD or hard drive)" },
                { q: "What do we call small, very fast memory located inside or close to the CPU that stores frequently used data?", a: "Cache" },
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
                storageKey: 'computerSystemsGame.settings',
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
                    return 'Think about which hardware component this describes, and whether it is volatile or non-volatile.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You've mastered computer systems!",
            });
        })();
    </script>
@endpush
