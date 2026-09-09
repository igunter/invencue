@extends('layouts.app')

@section('meta_title', 'Textures in Art — Art Game for Kids')
@section('meta_blurb', 'A free art game for young kids exploring textures used in art and materials — rough, smooth, bumpy, shiny and more.')
@section('meta_words', 'textures in art game, kids art game, rough smooth bumpy shiny, ks1 art game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-hand-index',
        'title' => 'Textures in Art',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Texture words'],
        ],
        'aboutTitle' => 'About this textures in art game',
        'aboutText' => 'This free art game helps young kids learn texture words used in art, like rough, smooth, bumpy and shiny, and match them to everyday examples.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Rough': 'A texture that feels bumpy or uneven, like tree bark or sandpaper.',
                'Smooth': 'A texture with no bumps, like glass or polished wood.',
                'Bumpy': 'A texture covered in small lumps, like the surface of an orange peel.',
                'Shiny': 'A texture that reflects light, like metal or glossy paint.',
                'Fluffy': "A soft, light texture, like cotton wool or a sheep's wool.",
                'Prickly': 'A sharp, pointed texture, like a cactus or a hedgehog.',
                'Soft': 'A gentle texture with no hard edges, like fur or fabric.',
                'Hard': "A firm, solid texture that doesn't bend, like stone or metal.",
                'Silky': 'A smooth, delicate texture, like fabric that glides under your fingers.',
                'Grainy': 'A texture made of tiny visible particles, like sand or wood grain.',
                'Sticky': 'A texture that clings to your fingers, like glue or honey.',
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
                storageKey: 'textures-in-artGame.settings',
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
                        questionText: "What does the texture word '" + term + "' mean?",
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Imagine touching this texture with your fingers — what would it feel like?';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You're a textures in art superstar!",
            });
        })();
    </script>
@endpush
