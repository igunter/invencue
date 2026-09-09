@extends('layouts.app')

@section('meta_title', 'European Countries — Geography Game for Kids')
@section('meta_blurb', 'A free geography game — match European countries to their capital cities.')
@section('meta_words', 'european countries game, geography game for kids, europe capitals quiz, countries of europe, ks2 geography')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-globe-europe-africa',
        'title' => 'European Countries',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'European capitals'],
        ],
        'aboutTitle' => 'About this European countries game',
        'aboutText' => 'This free geography game helps kids learn the countries of Europe and match each one to its capital city, building a clearer picture of the continent.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What is the capital city of Germany?", a: "Berlin" },
                { q: "What is the capital city of Spain?", a: "Madrid" },
                { q: "What is the capital city of Italy?", a: "Rome" },
                { q: "What is the capital city of Portugal?", a: "Lisbon" },
                { q: "What is the capital city of Greece?", a: "Athens" },
                { q: "What is the capital city of the Netherlands?", a: "Amsterdam" },
                { q: "What is the capital city of Poland?", a: "Warsaw" },
                { q: "What is the capital city of Sweden?", a: "Stockholm" },
                { q: "What is the capital city of Norway?", a: "Oslo" },
                { q: "What is the capital city of Ireland?", a: "Dublin" },
                { q: "What is the capital city of Switzerland?", a: "Bern" },
                { q: "What is the capital city of Austria?", a: "Vienna" },
                { q: "What is the capital city of Belgium, also home to the headquarters of the EU?", a: "Brussels" },
                { q: "What is the capital city of Finland?", a: "Helsinki" },
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
                storageKey: 'europeanCountriesGame.settings',
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
                    return 'Picture where in Europe this country is, and which city its government is run from.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a European countries superstar!",
            });
        })();
    </script>
@endpush
