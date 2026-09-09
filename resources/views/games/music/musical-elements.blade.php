@extends('layouts.app')

@section('meta_title', 'Musical Elements — GCSE Music Game')
@section('meta_blurb', 'A free GCSE music game covering texture, timbre, dynamics, tempo, structure and articulation.')
@section('meta_words', 'gcse musical elements game, texture timbre dynamics, tempo structure articulation, gcse music revision')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-sliders',
        'title' => 'Musical Elements',
        'subtitle' => 'Read the clue, then work out the term!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Element terms'],
        ],
        'aboutTitle' => 'About this musical elements game',
        'aboutText' => 'This free GCSE music game covers the key elements of music — texture, timbre, dynamics, tempo, structure and articulation — using standard GCSE Music terminology.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Texture': 'How musical layers or lines are combined, e.g. monophonic, homophonic or polyphonic.',
                'Monophonic texture': 'A single melodic line with no accompaniment or harmony.',
                'Homophonic texture': 'A melody with chordal accompaniment, moving together.',
                'Polyphonic texture': 'Two or more independent melodic lines happening at the same time.',
                'Timbre': 'The distinctive tone colour or quality of a sound, which lets you tell instruments apart.',
                'Dynamics': 'The volume of music, from very quiet (pianissimo) to very loud (fortissimo).',
                'Tempo': 'The speed of the music, from very slow (largo) to very fast (presto).',
                'Structure': 'The overall form or shape of a piece, e.g. verse-chorus, ternary or rondo form.',
                'Articulation': 'How notes are played or sung, e.g. staccato (short/detached) or legato (smooth/connected).',
                'Staccato': 'An articulation marking meaning notes should be played short and detached.',
                'Legato': 'An articulation marking meaning notes should be played smoothly connected.',
                'Ostinato': 'A short musical pattern that repeats persistently.',
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
                storageKey: 'musicalElementsGame.settings',
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
                        questionText: "What does '" + term + "' mean?",
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about which musical element — sound, speed, volume, layers, shape or attack — this term relates to.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered the elements of music!",
            });
        })();
    </script>
@endpush
