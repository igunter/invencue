@extends('layouts.app')

@section('meta_title', 'World War Two — GCSE History Game')
@section('meta_blurb', 'A free GCSE-level history game covering the Second World War — key events and dates.')
@section('meta_words', 'world war two game, gcse history game, ww2 quiz, dunkirk blitz d-day, second world war')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-shield',
        'title' => 'World War Two',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'WW2 facts'],
        ],
        'aboutTitle' => 'About this World War Two game',
        'aboutText' => 'This free history game covers key facts about the Second World War (1939–1945) — major events, from Dunkirk to D-Day.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "In which year did the Second World War begin?", a: "1939" },
                { q: "In which year did the Second World War end?", a: "1945" },
                { q: "What was the name of the mass evacuation of British and Allied soldiers from a French beach in 1940?", a: "The Dunkirk evacuation" },
                { q: "What name is given to the sustained bombing of British cities by the German air force in 1940\u201341?", a: "The Blitz" },
                { q: "What was the codename for the Allied invasion of Normandy in June 1944?", a: "D-Day" },
                { q: "Who was the Prime Minister who led Britain for most of the Second World War?", a: "Winston Churchill" },
                { q: "Who was the leader of Nazi Germany during the Second World War?", a: "Adolf Hitler" },
                { q: "What name is given to the Nazi persecution and mass murder of six million Jews and others during the war?", a: "The Holocaust" },
                { q: "Which surprise Japanese attack on a US naval base in 1941 brought America into the war?", a: "Pearl Harbor" },
                { q: "What day marks the Allied victory in Europe in May 1945?", a: "VE Day" },
                { q: "What weapon did the USA drop on Hiroshima and Nagasaki in 1945, leading to Japan's surrender?", a: "The atomic bomb" },
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
                storageKey: 'world-war-twoGame.settings',
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

                masteryMessage: "Amazing! You're a World War Two superstar!",
            });
        })();
    </script>
@endpush
