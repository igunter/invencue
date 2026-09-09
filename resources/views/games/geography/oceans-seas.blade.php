@extends('layouts.app')

@section('meta_title', 'Oceans & Seas — Geography Game for Kids')
@section('meta_blurb', 'A free geography game — learn famous oceans and seas and where in the world they are found.')
@section('meta_words', 'oceans and seas game, geography game for kids, famous seas quiz, world oceans, ks2 geography')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-tsunami',
        'title' => 'Oceans & Seas',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Oceans & seas'],
        ],
        'aboutTitle' => 'About this oceans & seas game',
        'aboutText' => 'This free geography game helps kids learn famous oceans and seas from around the world and where each one is found.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "The Mediterranean Sea lies between Europe and which other continent?", a: "Africa" },
                { q: "The Caribbean Sea lies off the coast of which region?", a: "Central America" },
                { q: "The Red Sea separates north-east Africa from which peninsula?", a: "The Arabian Peninsula" },
                { q: "The North Sea lies between the United Kingdom and which country's coastline (among others)?", a: "Norway" },
                { q: "The Black Sea lies between Europe and which region?", a: "Western Asia" },
                { q: "The South China Sea lies off the coast of which continent?", a: "Asia" },
                { q: "Which sea is famous for being so salty that people can easily float in it, and lies between Jordan and Israel?", a: "The Dead Sea" },
                { q: "The Baltic Sea lies in the north of which continent?", a: "Europe" },
                { q: "The Coral Sea, home to the Great Barrier Reef, lies off the north-east coast of which country?", a: "Australia" },
                { q: "The Arabian Sea forms part of which larger ocean?", a: "The Indian Ocean" },
                { q: "The Irish Sea lies between Great Britain and which other country?", a: "Ireland" },
                { q: "The English Channel separates England from which country?", a: "France" },
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
                storageKey: 'oceansSeasGame.settings',
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
                    return 'Picture a map of the world and think about which coastlines this body of water touches.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're an oceans and seas superstar!",
            });
        })();
    </script>
@endpush
