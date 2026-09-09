@extends('layouts.app')

@section('meta_title', 'Art Movements Intro — Art Game for Kids')
@section('meta_blurb', 'A free art game with simple facts about art movements — Impressionism, Cubism, Pop Art and more.')
@section('meta_words', 'art movements game, impressionism cubism pop art quiz, ks3 art game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-clock-history',
        'title' => 'Art Movements Intro',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Art movements'],
        ],
        'aboutTitle' => 'About this art movements intro game',
        'aboutText' => 'This free art game introduces well-known art movements, including Impressionism, Cubism and Pop Art, with simple facts about the style each one uses.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Impressionism': 'A style from the late 1800s using visible brushstrokes to capture light and everyday moments.',
                'Cubism': 'A style, pioneered by Picasso and Braque, that shows objects from many angles at once using geometric shapes.',
                'Pop Art': 'A style from the 1950s-60s using bright colours and images from adverts, comics and everyday products.',
                'Surrealism': "A style showing dreamlike, strange scenes that don't follow the rules of the real world.",
                'Realism': 'A style that shows people and scenes exactly as they look in real life.',
                'Abstract art': 'Art that uses shapes, colours and forms without trying to show a realistic scene.',
                'Renaissance': 'A period of art in Europe, from around the 1300s to 1600s, focused on realism and classical ideas.',
                'Expressionism': 'A style that distorts reality to show strong feelings and emotions.',
                'Fauvism': 'An early 1900s style using wild, unnatural colours, led by Henri Matisse.',
                'Folk art': 'Art made by ordinary people, often using traditional local styles and materials.',
            };
            const TERM_NAMES = Object.keys(TERMS);

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
                storageKey: 'art-movements-introGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const term = TERM_NAMES[randInt(0, TERM_NAMES.length - 1)];
                    return {
                        category: type,
                        label: term,
                        correctText: TERMS[term],
                        questionText: "What is '" + term + "'?",
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about the colours, shapes and subjects that make this style stand out.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You're an art movements superstar!",
            });
        })();
    </script>
@endpush
