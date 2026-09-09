@extends('layouts.app')

@section('meta_title', 'Flags of the World — Geography Game for Kids')
@section('meta_blurb', 'A free geography game — match a flag description to the country it belongs to.')
@section('meta_words', 'flags of the world game, geography game for kids, world flags quiz, country flags, ks2 geography')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-flag-fill',
        'title' => 'Flags of the World',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'World flags'],
        ],
        'aboutTitle' => 'About this flags of the world game',
        'aboutText' => 'This free geography game helps kids match a description of a country\'s flag to the country it belongs to, from stars and stripes to crosses and maple leaves.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "Which country's flag has 50 white stars and 13 red and white stripes?", a: "The USA" },
                { q: "Which country's flag has a red maple leaf in the centre of a white square, with red bars on each side?", a: "Canada" },
                { q: "Which country's flag is a horizontal tricolour of green, white and red?", a: "Italy" },
                { q: "Which country's flag is a vertical tricolour of blue, white and red?", a: "France" },
                { q: "Which country's flag is a red circle in the centre of a plain white background?", a: "Japan" },
                { q: "Which country's flag is a red field with a large white cross that reaches all four edges?", a: "Switzerland" },
                { q: "Which country's flag is a blue field with a white saltire (diagonal) cross?", a: "Scotland" },
                { q: "Which country's flag is a horizontal tricolour of black, red and yellow (gold)?", a: "Germany" },
                { q: "Which country's flag is red and white with a large red five-pointed star and four small stars in the top corner?", a: "China" },
                { q: "Which country's flag features a blue horizontal stripe, a white stripe, and a large sun-like emblem in the centre?", a: "Argentina" },
                { q: "Which country's flag is a horizontal tricolour of green, yellow and blue, with 'Order and Progress' written inside a globe?", a: "Brazil" },
                { q: "Which country's flag combines the crosses of St George, St Andrew and St Patrick into one design?", a: "The United Kingdom" },
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
                storageKey: 'flagsOfTheWorldGame.settings',
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
                    return 'Picture the colours and shapes described, then think which country you have seen that flag for.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a world flags superstar!",
            });
        })();
    </script>
@endpush
