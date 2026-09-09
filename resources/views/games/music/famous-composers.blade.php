@extends('layouts.app')

@section('meta_title', 'Famous Composers — Music Game for Kids')
@section('meta_blurb', 'A free music game — learn about famous composers like Mozart, Beethoven, Bach and Vivaldi and what they are known for.')
@section('meta_words', 'famous composers game, mozart beethoven bach vivaldi, classical music quiz, ks3 music game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-person-badge',
        'title' => 'Famous Composers',
        'subtitle' => 'Read the clue, then work out the composer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Composer facts'],
        ],
        'aboutTitle' => 'About this famous composers game',
        'aboutText' => 'This free music game introduces famous composers such as Mozart, Beethoven, Bach and Vivaldi, and what they are best known for.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "Which composer, deaf later in life, wrote the famous 'Ode to Joy' in his Ninth Symphony?", a: "Ludwig van Beethoven" },
                { q: "Which composer was a child prodigy from Austria who wrote over 600 works before dying young?", a: "Wolfgang Amadeus Mozart" },
                { q: "Which Baroque composer wrote 'The Four Seasons', a set of violin concertos?", a: "Antonio Vivaldi" },
                { q: "Which composer wrote huge amounts of church music and the Brandenburg Concertos?", a: "Johann Sebastian Bach" },
                { q: "Which Russian composer wrote the ballets 'Swan Lake' and 'The Nutcracker'?", a: "Pyotr Ilyich Tchaikovsky" },
                { q: "Which composer wrote the operas 'The Marriage of Figaro' and 'The Magic Flute'?", a: "Wolfgang Amadeus Mozart" },
                { q: "Which Romantic composer, from Poland, was famous for his nocturnes for piano?", a: "Frederic Chopin" },
                { q: "Which German composer wrote the famous 'Wedding March'?", a: "Felix Mendelssohn" },
                { q: "Which composer wrote 'The Planets', a suite inspired by astrology?", a: "Gustav Holst" },
                { q: "Which English composer wrote 'Land of Hope and Glory'?", a: "Edward Elgar" },
                { q: "Which composer wrote the opera 'Carmen'?", a: "Georges Bizet" },
                { q: "Which Hungarian composer was famous for his rhapsodies and virtuoso piano playing?", a: "Franz Liszt" },
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
                storageKey: 'famousComposersGame.settings',
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
                    return 'Think about the era this composer worked in and what they are most famous for.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a famous composers superstar!",
            });
        })();
    </script>
@endpush
