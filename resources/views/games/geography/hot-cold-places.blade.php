@extends('layouts.app')

@section('meta_title', 'Hot & Cold Places — Geography Game for Kids')
@section('meta_blurb', 'A free geography game for young kids — learn about the equator, the poles, and why some places are hot and some are cold.')
@section('meta_words', 'hot and cold places game, geography game for kids, equator north pole south pole, climate basics, ks1 ks2 geography')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-thermometer-half',
        'title' => 'Hot & Cold Places',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Hot & cold facts'],
        ],
        'aboutTitle' => 'About this hot & cold places game',
        'aboutText' => 'This free geography game helps young kids learn the basics of why some places on Earth are hot and some are cold, including the equator, the North and South Poles, and the hottest and coldest kinds of places on our planet.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What is the name of the imaginary line around the middle of the Earth, where it is usually hottest?", a: "The equator" },
                { q: "What is the name for the very cold, icy area at the very top of the Earth?", a: "The North Pole" },
                { q: "What is the name for the very cold, icy continent at the very bottom of the Earth?", a: "The South Pole (Antarctica)" },
                { q: "Places near the equator tend to be hot because the Sun's rays hit them more directly — true or false?", a: "True" },
                { q: "Places near the poles tend to be cold because the Sun's rays hit them at a low, spread-out angle — true or false?", a: "True" },
                { q: "What is the name for the hot, sandy places that get very little rain?", a: "Deserts" },
                { q: "What is the name for the huge frozen sheets of ice found near the poles?", a: "Ice caps" },
                { q: "Which is generally hotter: a place near the equator, or a place near the North Pole?", a: "A place near the equator" },
                { q: "What do we call the imaginary lines that circle the globe just north and south of the equator, marking the hottest zone?", a: "The Tropics" },
                { q: "Polar bears live in which kind of cold place?", a: "Near the North Pole (the Arctic)" },
                { q: "Penguins in the wild live mainly near which cold place?", a: "The South Pole (Antarctica)" },
                { q: "As you travel from the equator towards the poles, does the climate generally get warmer or colder?", a: "Colder" },
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
                storageKey: 'hotColdPlacesGame.settings',
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
                    return 'Think about how close to the equator or the poles the place is.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a hot and cold places superstar!",
            });
        })();
    </script>
@endpush
