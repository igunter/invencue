@extends('layouts.app')

@section('meta_title', 'Famous Artists — Art Game for Kids')
@section('meta_blurb', 'A free art game covering famous artists — Van Gogh, Picasso, Monet, Da Vinci, Frida Kahlo and more, and what they\'re known for.')
@section('meta_words', 'famous artists game, kids art game, van gogh picasso monet quiz, ks3 art game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-person-badge',
        'title' => 'Famous Artists',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Famous artists'],
        ],
        'aboutTitle' => 'About this famous artists game',
        'aboutText' => 'This free art game covers well-known artists, from Van Gogh and Picasso to Monet, Da Vinci and Frida Kahlo, and what each one is best known for.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Vincent van Gogh': 'A Dutch painter known for bold brushstrokes and works like Starry Night and Sunflowers.',
                'Pablo Picasso': 'A Spanish artist who co-founded Cubism and painted Guernica.',
                'Claude Monet': 'A French painter and founder of Impressionism, known for Water Lilies.',
                'Leonardo da Vinci': 'An Italian Renaissance artist who painted the Mona Lisa and The Last Supper.',
                'Frida Kahlo': 'A Mexican artist famous for vivid, symbolic self-portraits.',
                'Michelangelo': 'An Italian Renaissance artist who painted the Sistine Chapel ceiling and sculpted David.',
                'Andy Warhol': "An American artist known for Pop Art works like Campbell's Soup Cans.",
                "Georgia O'Keeffe": 'An American artist famous for large-scale paintings of flowers and desert landscapes.',
                'Rembrandt': 'A Dutch painter known for dramatic light and shadow in portraits like The Night Watch.',
                'Salvador Dalí': 'A Spanish Surrealist artist known for dreamlike paintings like The Persistence of Memory.',
                'Henri Matisse': 'A French artist known for bold colour and paper cut-out artworks.',
                'Katsushika Hokusai': 'A Japanese artist famous for the print The Great Wave off Kanagawa.',
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
                storageKey: 'famous-artistsGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const artist = TERM_NAMES[randInt(0, TERM_NAMES.length - 1)];
                    return {
                        category: type,
                        label: artist,
                        correctText: TERMS[artist],
                        questionText: "What is " + artist + " known for?",
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about this artist\'s home country, art style, and most famous works.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You're a famous artists superstar!",
            });
        })();
    </script>
@endpush
