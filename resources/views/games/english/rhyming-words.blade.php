@extends('layouts.app')

@section('meta_title', 'Rhyming Words — English Game for Kids')
@section('meta_blurb', 'A free English game for kids — pick the word that rhymes with the word shown.')
@section('meta_words', 'rhyming words game, rhyme game for kids, phonics game, ks1 english game, rhyming pairs')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-music-note-beamed',
        'title' => 'Rhyming Words',
        'subtitle' => 'Read the word, then pick the one that rhymes!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Rhyming words'],
        ],
        'aboutTitle' => 'About this rhyming words game',
        'aboutText' => 'This free English game helps young kids practise rhyming by picking the word that rhymes with a given word — a great way to build phonics skills and an ear for sounds.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "Which word rhymes with 'cat'?", a: "Hat" },
                { q: "Which word rhymes with 'dog'?", a: "Frog" },
                { q: "Which word rhymes with 'sun'?", a: "Fun" },
                { q: "Which word rhymes with 'tree'?", a: "Bee" },
                { q: "Which word rhymes with 'book'?", a: "Look" },
                { q: "Which word rhymes with 'star'?", a: "Car" },
                { q: "Which word rhymes with 'mouse'?", a: "House" },
                { q: "Which word rhymes with 'cake'?", a: "Lake" },
                { q: "Which word rhymes with 'ball'?", a: "Wall" },
                { q: "Which word rhymes with 'light'?", a: "Night" },
                { q: "Which word rhymes with 'shoe'?", a: "Blue" },
                { q: "Which word rhymes with 'rain'?", a: "Train" },
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
                storageKey: 'rhyming-wordsGame.settings',
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
                    return 'Say the words out loud — do the endings sound the same?';
                },

                explanationFor: function(q) {
                    return q.correctText + ' rhymes with the word shown.';
                },

                masteryMessage: "Amazing! You're a rhyming words superstar!",
            });
        })();
    </script>
@endpush
