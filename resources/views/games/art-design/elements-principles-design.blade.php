@extends('layouts.app')

@section('meta_title', 'Elements & Principles of Design — GCSE Art & Design Game')
@section('meta_blurb', 'A free GCSE Art & Design game covering the elements and principles of design — contrast, balance, rhythm, emphasis and more.')
@section('meta_words', 'gcse elements and principles of design game, contrast balance rhythm emphasis proportion unity, gcse art and design')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-diagram-3',
        'title' => 'Elements & Principles of Design',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Elements & principles'],
        ],
        'aboutTitle' => 'About this elements & principles of design game',
        'aboutText' => 'This free GCSE Art & Design game covers the formal elements and principles of design, including line, shape, form, colour and texture, plus contrast, balance, rhythm, emphasis, proportion and unity.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Contrast': 'A principle of design using differences, such as light against dark, to create visual interest.',
                'Balance': 'A principle of design about how the visual weight of elements is arranged across a composition.',
                'Rhythm': 'A principle of design created by repeating elements to lead the eye through an artwork.',
                'Emphasis': 'A principle of design used to make one part of an artwork stand out as the focal point.',
                'Proportion': 'A principle of design concerning the size relationship between different parts of an artwork.',
                'Unity': 'A principle of design where all parts of an artwork work together to feel like a whole.',
                'Line': 'An element of design — a mark made by a moving point, used to define edges and shapes.',
                'Shape': 'An element of design — a flat, enclosed area defined by edges or colour.',
                'Form': 'An element of design — the three-dimensional quality of an object, having height, width and depth.',
                'Colour': 'An element of design made up of hue, value and intensity.',
                'Texture': 'An element of design describing how a surface looks or feels, real or implied.',
                'Space': 'An element of design referring to the area around, between and within objects in an artwork.',
            };
            const TERM_NAMES = Object.keys(TERMS);

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
                storageKey: 'elements-principles-designGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const term = TERM_NAMES[randInt(0, TERM_NAMES.length - 1)];
                    return {
                        category: type,
                        label: term,
                        correctText: TERMS[term],
                        questionText: "What is '" + term + "' in design?",
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Decide whether this is a building block of an artwork, or a rule about how those building blocks are arranged.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered the elements & principles of design!",
            });
        })();
    </script>
@endpush
