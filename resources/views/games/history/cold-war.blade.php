@extends('layouts.app')

@section('meta_title', 'Cold War — GCSE History Game')
@section('meta_blurb', 'A free GCSE-level history game covering the Cold War — the USA, the Soviet Union and the nuclear arms race.')
@section('meta_words', 'cold war game, gcse history game, berlin wall cuban missile crisis quiz, usa soviet union')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-lock',
        'title' => 'Cold War',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Cold War facts'],
        ],
        'aboutTitle' => 'About this Cold War game',
        'aboutText' => 'This free history game covers key facts about the Cold War — the decades-long tension between the USA and the Soviet Union after the Second World War.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What term describes the decades-long tension between the USA and the Soviet Union after World War Two?", a: "The Cold War" },
                { q: "What was the name of the wall built in 1961 to divide a German city in two?", a: "The Berlin Wall" },
                { q: "What 1962 crisis brought the USA and Soviet Union close to nuclear war over missiles in the Caribbean?", a: "The Cuban Missile Crisis" },
                { q: "What term describes the military buildup of weapons between two rival superpowers, without direct fighting?", a: "The arms race" },
                { q: "What is the name of the plan the USA used to give economic aid to rebuild Western Europe after the war?", a: "The Marshall Plan" },
                { q: "In which year did the Berlin Wall fall?", a: "1989" },
                { q: "What is the name of the military alliance formed in 1949 by the USA, Canada and Western European countries?", a: "NATO" },
                { q: "What was the name of the rival military alliance formed by the Soviet Union and its Eastern European allies?", a: "The Warsaw Pact" },
                { q: "What term, popularised by Winston Churchill, describes the political divide separating Soviet-controlled Eastern Europe from the West?", a: "The Iron Curtain" },
                { q: "What name is given to the competition between the USA and Soviet Union to achieve space exploration milestones first?", a: "The Space Race" },
                { q: "Which Soviet cosmonaut became the first human in space in 1961?", a: "Yuri Gagarin" },
                { q: "In which country was there a major Cold War conflict during the 1950s and 1960s-70s involving the USA fighting communist forces in Southeast Asia?", a: "Vietnam" },
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
                storageKey: 'cold-warGame.settings',
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

                masteryMessage: "Amazing! You're a Cold War superstar!",
            });
        })();
    </script>
@endpush
