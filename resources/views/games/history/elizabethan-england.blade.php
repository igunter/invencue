@extends('layouts.app')

@section('meta_title', 'Elizabethan England — GCSE History Game')
@section('meta_blurb', 'A free GCSE-level history game covering Elizabethan England — the Spanish Armada, exploration and the arts.')
@section('meta_words', 'elizabethan england game, gcse history game, spanish armada shakespeare quiz, elizabeth i')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-gem',
        'title' => 'Elizabethan England',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Elizabethan facts'],
        ],
        'aboutTitle' => 'About this Elizabethan England game',
        'aboutText' => 'This free history game covers key facts about Elizabethan England — the reign of Elizabeth I, the defeat of the Spanish Armada, exploration and the arts.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "In which year did Elizabeth I become Queen of England?", a: "1558" },
                { q: "What was the name of the Spanish naval force defeated by England in 1588?", a: "The Spanish Armada" },
                { q: "Which famous playwright wrote plays such as Hamlet and Romeo and Juliet during Elizabeth's reign?", a: "William Shakespeare" },
                { q: "Which explorer, later knighted by Elizabeth I, was the first Englishman to sail around the world?", a: "Sir Francis Drake" },
                { q: "Elizabeth I never married and was sometimes known by what nickname?", a: "The Virgin Queen" },
                { q: "What term is used for the flourishing of English drama, poetry and music during Elizabeth's reign?", a: "The Elizabethan era" },
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
                storageKey: 'elizabethan-englandGame.settings',
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

                masteryMessage: "Amazing! You're an Elizabethan England superstar!",
            });
        })();
    </script>
@endpush
