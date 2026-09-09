@extends('layouts.app')

@section('meta_title', 'Ancient Greece — History Game for Kids')
@section('meta_blurb', 'A free history game covering ancient Greece — democracy, the Olympic Games, philosophers and myths.')
@section('meta_words', 'ancient greece game, history game for kids, democracy olympics quiz, greek gods philosophers, ks2 history game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-building',
        'title' => 'Ancient Greece',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Greece facts'],
        ],
        'aboutTitle' => 'About this ancient Greece game',
        'aboutText' => 'This free history game covers key facts about ancient Greece — democracy, the Olympic Games, famous philosophers and Greek myths.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What is the name of the ancient Greek system of government where citizens could vote?", a: "Democracy" },
                { q: "What were the sporting contests held every four years in ancient Greece called?", a: "The Olympic Games" },
                { q: "Who was the ancient Greek philosopher who taught Alexander the Great?", a: "Aristotle" },
                { q: "What is the name of the ancient Greek city famous as the birthplace of democracy?", a: "Athens" },
                { q: "What is the name of the mountain the ancient Greeks believed their gods lived on?", a: "Mount Olympus" },
                { q: "What is the name of the giant wooden horse used to trick the city of Troy?", a: "The Trojan Horse" },
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
                storageKey: 'ancient-greeceGame.settings',
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

                masteryMessage: "Amazing! You're an ancient Greece superstar!",
            });
        })();
    </script>
@endpush
