@extends('layouts.app')

@section('meta_title', 'Homes Through Time — History Game for Kids')
@section('meta_blurb', 'A free history game for kids — match a description of a home to the time period it belongs to.')
@section('meta_words', 'homes through time game, history game for kids, houses in history, stone age tudor victorian homes, primary school history')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-house-door',
        'title' => 'Homes Through Time',
        'subtitle' => 'Read the clue, then pick the right time period!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Home facts'],
        ],
        'aboutTitle' => 'About this homes through time game',
        'aboutText' => 'This free history game helps kids compare how homes have changed throughout British history, from Stone Age huts to modern houses.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "In which time period would you find homes made from branches and animal skins, or caves?", a: "Stone Age" },
                { q: "In which time period did wealthy homes called villas often have under-floor heating?", a: "Roman Britain" },
                { q: "In which time period did many homes have just one room with a fire in the middle and no chimney?", a: "Medieval Times" },
                { q: "In which time period did houses often have black wooden beams, white walls, and upper floors that stuck out over the street?", a: "Tudor Times" },
                { q: "In which time period were rows of new terraced houses built for workers in growing towns and cities?", a: "Victorian Times" },
                { q: "In which time period do most homes have electricity, running water and central heating?", a: "Modern Day" },
                { q: "In which time period did people live in longhouses built from wood, wattle and daub, often shared with animals?", a: "Anglo-Saxon Times" },
                { q: "In which time period were elegant, symmetrical brick townhouses with tall sash windows built for wealthy families?", a: "Georgian Times" },
                { q: "In which time period were many homes fitted with gas lighting for the first time and had an outdoor toilet?", a: "Victorian Times" },
                { q: "In which time period were many prefabricated homes and tower blocks built quickly to house families after bomb damage?", a: "Post-War Britain" },
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
                storageKey: 'homes-through-timeGame.settings',
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

                masteryMessage: "Amazing! You're a homes-through-time superstar!",
            });
        })();
    </script>
@endpush
