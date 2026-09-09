@extends('layouts.app')

@section('meta_title', 'Famous Explorers — History Game for Kids')
@section('meta_blurb', 'A free history game for kids — match famous explorers to what they discovered or achieved.')
@section('meta_words', 'explorers game, history game for kids, columbus magellan armstrong, famous explorers quiz, primary school history')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-compass',
        'title' => 'Famous Explorers',
        'subtitle' => 'Read the clue, then pick the right explorer!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Explorer facts'],
        ],
        'aboutTitle' => 'About this explorers game',
        'aboutText' => 'This free history game introduces kids to some of the most famous explorers in history and what made them famous, from sailing the oceans to walking on the Moon.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "Which explorer sailed across the Atlantic Ocean and reached the Americas in 1492?", a: "Christopher Columbus" },
                { q: "Which explorer travelled from Europe to China along the Silk Road?", a: "Marco Polo" },
                { q: "Which explorer's expedition was the first to sail all the way around the world?", a: "Ferdinand Magellan" },
                { q: "Which explorer mapped the coasts of Australia and New Zealand?", a: "James Cook" },
                { q: "Which explorer was the first person to walk on the Moon?", a: "Neil Armstrong" },
                { q: "Which explorer was the first woman to fly solo across the Atlantic Ocean?", a: "Amelia Earhart" },
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
                storageKey: 'famous-explorersGame.settings',
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

                masteryMessage: "Amazing! You're an explorers superstar!",
            });
        })();
    </script>
@endpush
