@extends('layouts.app')

@section('meta_title', 'Famous Artists & Their Work — GCSE Art & Design Game')
@section('meta_blurb', 'A free GCSE Art & Design game covering major artists in depth and the famous works they created.')
@section('meta_words', 'gcse famous artists game, artists and their work quiz, gcse art and design')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-easel',
        'title' => 'Famous Artists & Their Work',
        'subtitle' => 'Pick your question types, then test your knowledge!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'artists', 'label' => 'Artist facts'],
            ['id' => 'works', 'label' => 'Who made it?'],
        ],
        'aboutTitle' => 'About this famous artists & their work game',
        'aboutText' => 'This free GCSE Art & Design game covers major artists in more depth, what each is known for, and which artist created some of the world\'s most famous artworks.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const ARTISTS = {
                'Leonardo da Vinci': 'An Italian Renaissance polymath known for scientific studies and paintings including the Mona Lisa.',
                'Michelangelo': 'An Italian Renaissance sculptor and painter, known for the Sistine Chapel ceiling and the sculpture David.',
                'Rembrandt van Rijn': 'A Dutch Golden Age painter celebrated for his mastery of light, shadow and portraiture.',
                'Vincent van Gogh': 'A Dutch Post-Impressionist known for expressive colour and brushwork despite little success in his lifetime.',
                'Claude Monet': 'A French Impressionist who founded the movement and painted series like Water Lilies and Haystacks.',
                'Pablo Picasso': 'A Spanish artist who co-founded Cubism and worked across painting, sculpture and ceramics.',
                'Frida Kahlo': 'A Mexican painter known for symbolic self-portraits exploring identity, pain and Mexican culture.',
                'Salvador Dalí': 'A Spanish Surrealist known for precise technique applied to bizarre, dreamlike imagery.',
                'Andy Warhol': 'An American Pop artist who used mass-production techniques to depict celebrities and consumer goods.',
                "Georgia O'Keeffe": 'An American Modernist known for close-up paintings of flowers and New Mexico landscapes.',
            };
            const ARTIST_NAMES = Object.keys(ARTISTS);

            const WORKS = {
                'Mona Lisa': 'Leonardo da Vinci',
                'The Last Supper': 'Leonardo da Vinci',
                'David': 'Michelangelo',
                'The Night Watch': 'Rembrandt van Rijn',
                'The Starry Night': 'Vincent van Gogh',
                'Water Lilies': 'Claude Monet',
                'Guernica': 'Pablo Picasso',
                'The Two Fridas': 'Frida Kahlo',
                'The Persistence of Memory': 'Salvador Dalí',
                "Campbell's Soup Cans": 'Andy Warhol',
            };
            const WORK_NAMES = Object.keys(WORKS);

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
                storageKey: 'famous-artists-workGame.settings',
                types: ['artists', 'works'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'artists') {
                        const artist = ARTIST_NAMES[randInt(0, ARTIST_NAMES.length - 1)];
                        return {
                            category: type,
                            label: artist,
                            correctText: ARTISTS[artist],
                            questionText: "What is " + artist + " known for?",
                        };
                    }
                    const work = WORK_NAMES[randInt(0, WORK_NAMES.length - 1)];
                    return {
                        category: type,
                        label: work,
                        correctText: WORKS[work],
                        questionText: "Which artist created '" + work + "'?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'artists') {
                        const distractors = pickOthers(ARTIST_NAMES, q.label, 3).map(function(t) { return ARTISTS[t]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(WORK_NAMES, q.label, 3).map(function(t) { return WORKS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'artists') {
                        return 'Think about this artist\'s home country, art movement, and most famous works.';
                    }
                    return 'Think about which artist and art movement this work is most associated with.';
                },

                explanationFor: function(q) {
                    if (q.category === 'works') return "'" + q.label + "' was created by " + q.correctText + '.';
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered famous artists and their work!",
            });
        })();
    </script>
@endpush
