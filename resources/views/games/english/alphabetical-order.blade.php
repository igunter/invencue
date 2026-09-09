@extends('layouts.app')

@section('meta_title', 'Alphabetical Order — English Game for Kids')
@section('meta_blurb', 'A free English game for kids — pick which word comes first in alphabetical order.')
@section('meta_words', 'alphabetical order game, abc order game for kids, dictionary skills, ks1 english game, alphabet game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-sort-alpha-down',
        'title' => 'Alphabetical Order',
        'subtitle' => 'Pick two words, then say which comes first!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Which comes first?'],
        ],
        'aboutTitle' => 'About this alphabetical order game',
        'aboutText' => 'This free English game helps young kids practise putting words in alphabetical order — a useful skill for using dictionaries, indexes and word lists.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const ITEMS = [
                { name: "Apple", order: 1 },
                { name: "Banana", order: 2 },
                { name: "Cat", order: 3 },
                { name: "Dog", order: 4 },
                { name: "Elephant", order: 5 },
                { name: "Fish", order: 6 },
                { name: "Goat", order: 7 },
                { name: "House", order: 8 },
                { name: "Igloo", order: 9 },
                { name: "Jelly", order: 10 },
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
                storageKey: 'alphabetical-orderGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-6',

                buildQuestion: function(type) {
                    let a, b;
                    do {
                        a = ITEMS[randInt(0, ITEMS.length - 1)];
                        b = ITEMS[randInt(0, ITEMS.length - 1)];
                    } while (a.name === b.name);
                    const correct = a.order < b.order ? a.name : b.name;
                    return {
                        category: type,
                        a: a.name,
                        b: b.name,
                        correctText: correct,
                        questionText: 'Which word comes first in alphabetical order: ' + a.name + ' or ' + b.name + '?',
                    };
                },

                buildChoices: function(q) {
                    return shuffle([q.a, q.b]);
                },

                hintFor: function() {
                    return 'Look at the very first letter of each word — which one comes first in the alphabet?';
                },

                explanationFor: function(q) {
                    return q.correctText + ' comes first alphabetically.';
                },

                masteryMessage: "Amazing! You're an alphabetical order superstar!",
            });
        })();
    </script>
@endpush
