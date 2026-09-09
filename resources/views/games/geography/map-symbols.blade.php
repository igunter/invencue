@extends('layouts.app')

@section('meta_title', 'Map Symbols — Geography Game for Kids')
@section('meta_blurb', 'A free geography game for young kids — learn what common map key symbols mean.')
@section('meta_words', 'map symbols game, geography game for kids, map key, ordnance survey symbols, ks1 ks2 geography')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-signpost-2',
        'title' => 'Map Symbols',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Map symbols'],
        ],
        'aboutTitle' => 'About this map symbols game',
        'aboutText' => 'This free geography game helps young kids learn what common map key symbols mean, from churches and railways to rivers, forests and car parks — an important first step in learning to read maps.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "On a map, a small cross inside a circle usually shows what?", a: "A church" },
                { q: "On a map, a blue wavy line usually shows what?", a: "A river" },
                { q: "On a map, a cluster of green tree symbols usually shows what?", a: "A forest or woodland" },
                { q: "On a map, a symbol showing two parallel lines with cross-hatches usually shows what?", a: "A railway line" },
                { q: "On a map, a blue letter 'P' usually shows what?", a: "A car park" },
                { q: "On a map, a small tent symbol usually shows what?", a: "A campsite" },
                { q: "On a map, a small aeroplane symbol usually shows what?", a: "An airport" },
                { q: "On a map, a symbol of a knife and fork usually shows what?", a: "A restaurant or picnic site" },
                { q: "On a map, a blue anchor symbol usually shows what?", a: "A harbour or marina" },
                { q: "What is the name for the box on a map that explains what each symbol means?", a: "The key (or legend)" },
                { q: "On a map, a red cross symbol usually shows what?", a: "A hospital" },
                { q: "On a map, a small building with a flag on top often shows what?", a: "A castle" },
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
                storageKey: 'mapSymbolsGame.settings',
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
                    return 'Picture the shape and colour of the symbol, then think about what it looks like in real life.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a map symbols superstar!",
            });
        })();
    </script>
@endpush
