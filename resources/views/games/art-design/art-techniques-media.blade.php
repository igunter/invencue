@extends('layouts.app')

@section('meta_title', 'Art Techniques & Media — GCSE Art & Design Game')
@section('meta_blurb', 'A free GCSE Art & Design game covering media types and techniques — etching, tempera, watercolour, mixed media and more.')
@section('meta_words', 'gcse art techniques game, art media quiz, etching tempera watercolour mixed media, gcse art and design')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-droplet',
        'title' => 'Art Techniques & Media',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Techniques & media'],
        ],
        'aboutTitle' => 'About this art techniques & media game',
        'aboutText' => 'This free GCSE Art & Design game covers key media types and techniques, including etching, tempera, watercolour, oil paint, mixed media and more.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Etching': 'A printmaking technique where lines are cut into a metal plate using acid, then inked and printed.',
                'Tempera': 'A fast-drying paint made by mixing pigment with egg yolk, used widely before oil paint became common.',
                'Watercolour': 'A paint made from pigment and water that produces light, transparent washes of colour.',
                'Oil paint': 'A slow-drying paint made from pigment mixed with oil, allowing rich colour and blending.',
                'Acrylic paint': 'A fast-drying, water-based paint that can be used thickly or thinly like watercolour.',
                'Mixed media': 'Artwork made by combining more than one material or technique, such as paint and collage.',
                'Gouache': 'An opaque watercolour paint that dries with a flat, matte finish.',
                'Charcoal': 'A drawing material made from burnt wood, used for bold, smudgeable dark lines and tones.',
                'Collage': 'Artwork made by sticking different materials, like paper or fabric, onto a surface.',
                'Impasto': 'A technique of applying paint thickly so brush or palette knife marks stay visible.',
                'Fresco': 'A technique of painting onto wet plaster so the pigment becomes part of the wall as it dries.',
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
                storageKey: 'art-techniques-mediaGame.settings',
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
                    return 'Think about what the medium is made from, and how it is applied or created.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered art techniques & media!",
            });
        })();
    </script>
@endpush
