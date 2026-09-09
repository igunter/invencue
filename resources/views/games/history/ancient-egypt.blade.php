@extends('layouts.app')

@section('meta_title', 'Ancient Egypt — History Game for Kids')
@section('meta_blurb', 'A free history game covering ancient Egypt — pyramids, pharaohs, mummification and hieroglyphics.')
@section('meta_words', 'ancient egypt game, history game for kids, pyramids pharaohs quiz, hieroglyphics mummification, ks2 history game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-sun',
        'title' => 'Ancient Egypt',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Egypt facts'],
        ],
        'aboutTitle' => 'About this ancient Egypt game',
        'aboutText' => 'This free history game covers key facts about ancient Egypt, one of the world\'s earliest civilisations — pyramids, pharaohs, mummification, hieroglyphics and the River Nile.',
    ])
@endsection

@php
    $factsForJs = $dbQuestions->get('main', collect())->map(function ($question) {
        return array_filter([
            'q' => $question->question,
            'a' => $question->answer,
            'options' => $question->choices,
        ]);
    })->values();
@endphp

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = @json($factsForJs);

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
                storageKey: 'ancient-egyptGame.settings',
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
                    return 'Think carefully about the topic and time period.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're an ancient Egypt superstar!",
            });
        })();
    </script>
@endpush
