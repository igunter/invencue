@extends('layouts.app')

@section('meta_title', 'Kings & Queens — History Game for Kids')
@section('meta_blurb', 'A free history game for kids — match famous kings and queens to what they are best known for.')
@section('meta_words', 'kings and queens game, history game for kids, british monarchs quiz, henry viii elizabeth victoria, primary school history')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-gem',
        'title' => 'Kings & Queens',
        'subtitle' => 'Read the clue, then pick the right king or queen!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Royal facts'],
        ],
        'aboutTitle' => 'About this kings and queens game',
        'aboutText' => 'This free history game introduces kids to some of the most famous kings and queens in British history and what they are best remembered for.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "Which king had six wives and started the Church of England?", a: "Henry VIII" },
                { q: "Which queen ruled England during the defeat of the Spanish Armada?", a: "Elizabeth I" },
                { q: "Which queen ruled for so long that an entire era is named after her?", a: "Queen Victoria" },
                { q: "Which queen was the longest-reigning monarch in British history?", a: "Elizabeth II" },
                { q: "Which king became King of England after the Battle of Hastings in 1066?", a: "William the Conqueror" },
                { q: "Who is the current King of the United Kingdom?", a: "King Charles III" },
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
                storageKey: 'kings-queensGame.settings',
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

                masteryMessage: "Amazing! You're a royal history superstar!",
            });
        })();
    </script>
@endpush
