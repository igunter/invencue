@extends('layouts.app')

@section('meta_title', 'Music History & Eras — GCSE Music Game')
@section('meta_blurb', 'A free GCSE music game covering the Baroque, Classical, Romantic and 20th century/contemporary eras and their features.')
@section('meta_words', 'gcse music history game, baroque classical romantic era, 20th century music, gcse music revision')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-hourglass-split',
        'title' => 'Music History & Eras',
        'subtitle' => 'Read the clue, then work out the era!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Era facts'],
        ],
        'aboutTitle' => 'About this music history & eras game',
        'aboutText' => 'This free GCSE music game covers the Baroque, Classical, Romantic and 20th century/contemporary eras, and the musical features that define each one.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "Which era, roughly 1600-1750, features ornamented melodies and composers like Bach and Vivaldi?", a: "The Baroque era" },
                { q: "Which era, roughly 1750-1820, is known for balanced phrases and composers like Mozart and Haydn?", a: "The Classical era" },
                { q: "Which era, roughly 1820-1900, is known for expressive melodies and large orchestras, with composers like Tchaikovsky?", a: "The Romantic era" },
                { q: "Which era, from around 1900 to today, includes experimental and minimalist styles?", a: "The 20th century/contemporary era" },
                { q: "In which era did composers commonly use terraced dynamics, switching suddenly between loud and soft?", a: "The Baroque era" },
                { q: "In which era did the symphony orchestra grow to its largest, most powerful size?", a: "The Romantic era" },
                { q: "In which era was the harpsichord commonly used, before the piano became dominant?", a: "The Baroque era" },
                { q: "In which era did composers like Mozart favour clear, balanced phrases often in four-bar patterns?", a: "The Classical era" },
                { q: "In which era did composers begin experimenting with atonality, moving away from traditional keys?", a: "The 20th century/contemporary era" },
                { q: "In which era did programme music, which tells a story or paints a picture, become especially popular?", a: "The Romantic era" },
                { q: "In which era did the string quartet and symphony forms become firmly established?", a: "The Classical era" },
                { q: "In which era did minimalism, with its repeating patterns, emerge as a style?", a: "The 20th century/contemporary era" },
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
                storageKey: 'musicHistoryErasGame.settings',
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
                    return 'Think about roughly when this era took place and which composers worked in it.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a music history and eras superstar!",
            });
        })();
    </script>
@endpush
