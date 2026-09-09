@extends('layouts.app')

@section('meta_title', 'Ancient Rome — History Game for Kids')
@section('meta_blurb', 'A free history game covering ancient Rome — gladiators, legions, roads and the Roman Empire.')
@section('meta_words', 'ancient rome game, history game for kids, gladiators colosseum quiz, roman empire, ks2 history game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-bank',
        'title' => 'Ancient Rome',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Rome facts'],
        ],
        'aboutTitle' => 'About this ancient Rome game',
        'aboutText' => 'This free history game covers key facts about ancient Rome and its empire — gladiators, legions, roads, and famous leaders like Julius Caesar.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What were professional fighters who battled for entertainment in Roman arenas called?", a: "Gladiators" },
                { q: "What is the name of the large stone amphitheatre in Rome where gladiator fights took place?", a: "The Colosseum" },
                { q: "What were groups of around 5,000 Roman soldiers called?", a: "Legions" },
                { q: "What is the name of the huge wall the Romans built in the north of Britain?", a: "Hadrian's Wall" },
                { q: "Who was the famous Roman general and leader who was assassinated in 44 BC?", a: "Julius Caesar" },
                { q: "What term describes the network of paved roads the Romans built across their empire?", a: "Roman roads" },
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
                storageKey: 'ancient-romeGame.settings',
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

                masteryMessage: "Amazing! You're an ancient Rome superstar!",
            });
        })();
    </script>
@endpush
