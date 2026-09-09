@extends('layouts.app')

@section('meta_title', 'Music Around the World — Music Game for Kids')
@section('meta_blurb', 'A free music game for young kids — a simple introduction to traditional instruments and music styles from around the world.')
@section('meta_words', 'world music game for kids, traditional instruments quiz, music around the world, ks1 ks2 music game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-globe-americas',
        'title' => 'Music Around the World',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'World music facts'],
        ],
        'aboutTitle' => 'About this music around the world game',
        'aboutText' => 'This free music game gives young kids a simple introduction to traditional instruments and music styles from different countries and cultures around the world.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "Which country is the sitar, a long stringed instrument, traditionally from?", a: "India" },
                { q: "Which country are steel drums (steelpans) traditionally from?", a: "Trinidad and Tobago" },
                { q: "Which country is the didgeridoo, a long wooden wind instrument, traditionally from?", a: "Australia" },
                { q: "Which country is bagpipe music especially associated with?", a: "Scotland" },
                { q: "Which country is flamenco music and guitar traditionally from?", a: "Spain" },
                { q: "Which region is drumming and the djembe drum especially associated with?", a: "West Africa" },
                { q: "Which country is the koto, a long stringed instrument played flat on the floor, traditionally from?", a: "Japan" },
                { q: "Which country is mariachi band music traditionally from?", a: "Mexico" },
                { q: "Which country is reggae music originally from?", a: "Jamaica" },
                { q: "Which country are gamelan orchestras, made of gongs and metal instruments, traditionally from?", a: "Indonesia" },
                { q: "Which country is the banjo especially associated with in folk and country music?", a: "The United States" },
                { q: "Which country is Irish folk music, with fiddles and tin whistles, traditionally from?", a: "Ireland" },
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
                storageKey: 'musicAroundTheWorldGame.settings',
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
                    return 'Think about where in the world this instrument or music style comes from.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a music around the world superstar!",
            });
        })();
    </script>
@endpush
