@extends('layouts.app')

@section('meta_title', 'Art Critical Analysis Terms — GCSE Art & Design Game')
@section('meta_blurb', 'A free GCSE Art & Design game covering the terms used to analyse artwork — composition, form, tone, hue and saturation.')
@section('meta_words', 'gcse art critical analysis game, composition form tone hue saturation, gcse art and design')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-search',
        'title' => 'Art Critical Analysis Terms',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Analysis terms'],
        ],
        'aboutTitle' => 'About this art critical analysis terms game',
        'aboutText' => 'This free GCSE Art & Design game covers the vocabulary used to analyse artwork, including composition, form, tone, hue, saturation and negative space.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Composition': 'The way elements are arranged and organised within an artwork.',
                'Form': 'The three-dimensional structure of an object or figure in an artwork.',
                'Tone': 'The lightness or darkness of a colour or area within an artwork.',
                'Hue': 'The pure name of a colour, such as red, blue or yellow.',
                'Saturation': 'How pure, strong or dull a colour appears.',
                'Focal point': "The part of an artwork that first draws the viewer's eye.",
                'Medium': 'The material or technique used to create an artwork, such as oil paint or charcoal.',
                'Palette': 'The range of colours an artist chooses to use in an artwork.',
                'Perspective': 'The technique used to create the illusion of depth and distance in a two-dimensional artwork.',
                'Negative space': 'The empty space around and between the main subjects of an artwork.',
                'Value': 'How light or dark a colour is, from its lightest tint to its darkest shade.',
                'Aesthetic': 'Relating to the visual appeal or beauty of an artwork.',
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
                storageKey: 'art-critical-analysis-termsGame.settings',
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
                        questionText: "What does '" + term + "' mean when analysing an artwork?",
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about whether this term relates to arrangement, colour, light, or the material used.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered art critical analysis terms!",
            });
        })();
    </script>
@endpush
