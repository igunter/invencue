@extends('layouts.app')

@section('meta_title', 'Landmarks of the World — Geography Game for Kids')
@section('meta_blurb', 'A free geography game for young kids — match famous world landmarks to the country they are in.')
@section('meta_words', 'landmarks of the world game, geography game for kids, famous landmarks quiz, world landmarks countries, ks1 ks2 geography')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-bank',
        'title' => 'Landmarks of the World',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Landmarks'],
        ],
        'aboutTitle' => 'About this landmarks of the world game',
        'aboutText' => 'This free geography game helps young kids learn famous landmarks from around the world and which country each one is found in, from the Eiffel Tower to the Great Wall of China.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "The Eiffel Tower is a famous landmark in which country?", a: "France" },
                { q: "The Great Wall is a famous landmark in which country?", a: "China" },
                { q: "The Statue of Liberty is a famous landmark in which country?", a: "The USA" },
                { q: "The Pyramids of Giza are a famous landmark in which country?", a: "Egypt" },
                { q: "The Taj Mahal is a famous landmark in which country?", a: "India" },
                { q: "Big Ben and the Houses of Parliament are famous landmarks in which country?", a: "The United Kingdom" },
                { q: "The Colosseum is a famous landmark in which country?", a: "Italy" },
                { q: "The Sydney Opera House is a famous landmark in which country?", a: "Australia" },
                { q: "Christ the Redeemer statue is a famous landmark in which country?", a: "Brazil" },
                { q: "Mount Fuji is a famous landmark in which country?", a: "Japan" },
                { q: "The Leaning Tower of Pisa is a famous landmark in which country?", a: "Italy" },
                { q: "Machu Picchu is a famous ancient landmark in which country?", a: "Peru" },
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
                storageKey: 'landmarksOfTheWorldGame.settings',
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
                    return 'Picture the landmark, then think about which continent and country it stands in.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a world landmarks superstar!",
            });
        })();
    </script>
@endpush
