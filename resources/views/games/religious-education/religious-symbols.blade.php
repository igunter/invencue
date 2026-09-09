@extends('layouts.app')

@section('meta_title', 'Religious Symbols — Religious Education Game for Kids')
@section('meta_blurb', 'A free religious education game for kids — match symbols like the cross, Star of David and Khanda to the religion they belong to.')
@section('meta_words', 'religious symbols game, religious education game for kids, ks2 re game, cross star of david khanda om quiz')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-shapes',
        'title' => 'Religious Symbols',
        'subtitle' => 'Read the clue, then work out the religion!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Religious symbols'],
        ],
        'aboutTitle' => 'About this religious symbols game',
        'aboutText' => 'This free religious education game teaches young children about well-known symbols — the cross, the Star of David, the crescent moon and star, Om, the Khanda and more — and which religion each one belongs to.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const RELIGIONS = ['Christianity', 'Islam', 'Judaism', 'Hinduism', 'Buddhism', 'Sikhism'];

            const FACTS = [
                { q: "The cross is the best-known symbol of which religion?", a: "Christianity" },
                { q: "The Star of David is a well-known symbol of which religion?", a: "Judaism" },
                { q: "The crescent moon and star is often used as a symbol of which religion?", a: "Islam" },
                { q: "The 'Om' symbol is an important sacred sound and symbol in which religion?", a: "Hinduism" },
                { q: "The Khanda, made up of a double-edged sword and two curved blades, is the symbol of which religion?", a: "Sikhism" },
                { q: "The Dharma Wheel (a wheel with eight spokes) is an important symbol in which religion?", a: "Buddhism" },
                { q: "The Ichthys, a simple fish symbol, has long been used by followers of which religion?", a: "Christianity" },
                { q: "'Ik Onkar', meaning 'one God', is a sacred symbol in which religion?", a: "Sikhism" },
                { q: "The menorah, a seven-branched candle holder, is a symbol of which religion?", a: "Judaism" },
                { q: "The lotus flower is an important symbol in Hinduism and which other religion?", a: "Buddhism" },
                { q: "A crescent moon on top of a mosque's dome is a common symbol of which religion?", a: "Islam" },
                { q: "The fish and the cross are both symbols used by followers of which religion?", a: "Christianity" },
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

            function pickOthers(pool, exclude, count) {
                return shuffle(pool.filter(function(x) { return x !== exclude; })).slice(0, count);
            }

            window.ScienceQuiz.run({
                storageKey: 'religiousSymbolsGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const item = FACTS[randInt(0, FACTS.length - 1)];
                    return { category: type, correctText: item.a, questionText: item.q };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(RELIGIONS, q.correctText, 3);
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Picture the symbol in your mind — where have you seen it before?';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a religious symbols superstar!",
            });
        })();
    </script>
@endpush
