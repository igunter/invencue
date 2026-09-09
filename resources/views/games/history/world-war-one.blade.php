@extends('layouts.app')

@section('meta_title', 'World War One — GCSE History Game')
@section('meta_blurb', 'A free GCSE-level history game covering the First World War — causes, key events and dates.')
@section('meta_words', 'world war one game, gcse history game, ww1 quiz, trenches treaty of versailles, first world war')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-flag',
        'title' => 'World War One',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'WW1 facts'],
        ],
        'aboutTitle' => 'About this World War One game',
        'aboutText' => 'This free history game covers key facts about the First World War (1914–1918) — its causes, major events and how it ended.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "In which year did the First World War begin?", a: "1914" },
                { q: "In which year did the First World War end?", a: "1918" },
                { q: "The assassination of which Archduke is widely seen as the trigger for the start of the war?", a: "Archduke Franz Ferdinand" },
                { q: "What term describes the long ditches soldiers fought from on the Western Front?", a: "Trenches" },
                { q: "What name is given to the alliance of Britain, France and Russia during the war?", a: "The Allies" },
                { q: "What was the name of the treaty signed in 1919 that officially ended the war with Germany?", a: "The Treaty of Versailles" },
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
                storageKey: 'world-war-oneGame.settings',
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

                masteryMessage: "Amazing! You're a World War One superstar!",
            });
        })();
    </script>
@endpush
