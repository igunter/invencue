@extends('layouts.app')

@section('meta_title', 'Famous Paintings for Kids — Art Game for Kids')
@section('meta_blurb', 'A free art game for young kids — simple facts about a handful of the world\'s most famous paintings.')
@section('meta_words', 'famous paintings game, kids art game, mona lisa quiz, starry night, sunflowers, ks1 ks2 art game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-image',
        'title' => 'Famous Paintings for Kids',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Famous paintings'],
        ],
        'aboutTitle' => 'About this famous paintings game',
        'aboutText' => 'This free art game introduces young kids to a handful of the world\'s most famous paintings, matching each one to the artist who painted it.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'The Mona Lisa': 'Leonardo da Vinci',
                'The Starry Night': 'Vincent van Gogh',
                'Sunflowers': 'Vincent van Gogh',
                'The Scream': 'Edvard Munch',
                'Girl with a Pearl Earring': 'Johannes Vermeer',
                'The Hay Wain': 'John Constable',
                'Water Lilies': 'Claude Monet',
                'The Persistence of Memory': 'Salvador Dalí',
                'American Gothic': 'Grant Wood',
                'The Great Wave off Kanagawa': 'Hokusai',
                'Guernica': 'Pablo Picasso',
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
                storageKey: 'famous-paintings-kidsGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const painting = TERM_NAMES[randInt(0, TERM_NAMES.length - 1)];
                    return {
                        category: type,
                        label: painting,
                        correctText: TERMS[painting],
                        questionText: "Who painted '" + painting + "'?",
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about which famous artist is best known for this painting.';
                },

                explanationFor: function(q) {
                    return "'" + q.label + "' was painted by " + q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a famous paintings superstar!",
            });
        })();
    </script>
@endpush
