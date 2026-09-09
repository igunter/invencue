@extends('layouts.app')

@section('meta_title', 'Famous Composers & Their Works — GCSE Music Game')
@section('meta_blurb', 'A free GCSE music game covering major composers and their most famous pieces, in more depth than an introductory quiz.')
@section('meta_words', 'gcse composers game, famous works quiz, mozart beethoven stravinsky, gcse music revision')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-award',
        'title' => 'Famous Composers & Their Works',
        'subtitle' => 'Read the clue, then work out the composer!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Composer facts'],
        ],
        'aboutTitle' => 'About this famous composers & their works game',
        'aboutText' => 'This free GCSE music game covers major composers and their most famous individual pieces in more depth, from Bach and Mozart to Stravinsky and John Williams.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "Which composer wrote the opera 'The Magic Flute' and left his 'Requiem' unfinished at his death?", a: "Wolfgang Amadeus Mozart" },
                { q: "Which composer wrote Symphony No. 5, instantly recognisable for its four-note opening motif?", a: "Ludwig van Beethoven" },
                { q: "Which composer wrote 'The Well-Tempered Clavier', a collection of preludes and fugues in every key?", a: "Johann Sebastian Bach" },
                { q: "Which composer wrote the ballet 'The Rite of Spring', famous for causing a riot at its 1913 premiere?", a: "Igor Stravinsky" },
                { q: "Which composer wrote 'Clair de Lune', a famous piano piece associated with musical Impressionism?", a: "Claude Debussy" },
                { q: "Which composer wrote the opera 'La Bohème'?", a: "Giacomo Puccini" },
                { q: "Which composer wrote 'The Planets' suite, including the movement 'Mars, the Bringer of War'?", a: "Gustav Holst" },
                { q: "Which composer wrote 'Messiah', an oratorio containing the 'Hallelujah' chorus?", a: "George Frideric Handel" },
                { q: "Which composer wrote 'Peter and the Wolf', a piece introducing orchestral instruments to children?", a: "Sergei Prokofiev" },
                { q: "Which composer wrote Symphony No. 9, subtitled 'From the New World'?", a: "Antonin Dvorak" },
                { q: "Which minimalist composer wrote 'Music for 18 Musicians'?", a: "Steve Reich" },
                { q: "Which composer wrote the film scores for 'Star Wars' and 'Jaws'?", a: "John Williams" },
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
                storageKey: 'famousComposersWorksGame.settings',
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
                    return 'Think about the era this composer worked in and the style of the piece described.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a composers and their works superstar!",
            });
        })();
    </script>
@endpush
