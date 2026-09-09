@extends('layouts.app')

@section('meta_title', 'Sculpture & 3D Art — Art Game for Kids')
@section('meta_blurb', 'A free art game covering sculpture and 3D art — materials and techniques like carving, modelling and casting.')
@section('meta_words', 'sculpture game, 3d art quiz, carving modelling casting, clay sculpture, ks3 art game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-box',
        'title' => 'Sculpture & 3D Art',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Sculpture terms'],
        ],
        'aboutTitle' => 'About this sculpture & 3D art game',
        'aboutText' => 'This free art game covers materials and techniques used to make 3D art, including carving, modelling, casting and assemblage.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Clay': 'A soft, natural material that can be shaped by hand and then hardened by firing.',
                'Carving': 'Making a sculpture by cutting or chipping away material, such as stone or wood.',
                'Modelling': 'Making a sculpture by shaping and building up a soft material, like clay.',
                'Casting': 'Making a sculpture by pouring liquid material, like plaster or bronze, into a mould.',
                'Assemblage': 'A sculpture made by joining together different found objects or materials.',
                'Kiln': 'An oven used to fire and harden clay sculptures at high temperatures.',
                'Armature': 'A supporting frame, often wire, built inside a sculpture to hold its shape.',
                'Relief sculpture': 'A sculpture where the design sticks out from a flat background, rather than standing fully free.',
                'Freestanding sculpture': 'A sculpture that stands on its own and can be viewed from all sides.',
                'Mould': 'A hollow container used to shape liquid material into a sculpture as it hardens.',
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
                storageKey: 'sculpture-3d-artGame.settings',
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
                        questionText: "What is '" + term + "' in sculpture?",
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about whether material is added, taken away, poured, or joined together.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You're a sculpture & 3D art superstar!",
            });
        })();
    </script>
@endpush
