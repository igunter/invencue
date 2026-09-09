@extends('layouts.app')

@section('meta_title', 'Hinduism Basics — Religious Education Game for Kids')
@section('meta_blurb', 'A free religious education game covering the basics of Hinduism — key deities, the Vedas, the temple and key festivals.')
@section('meta_words', 'hinduism basics game, religious education game, ks3 re game, vishnu shiva vedas temple diwali quiz')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-flower1',
        'title' => 'Hinduism Basics',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Hinduism basics'],
        ],
        'aboutTitle' => 'About this Hinduism basics game',
        'aboutText' => 'This free religious education game covers the key beliefs, figures and practices of Hinduism — deities such as Vishnu and Shiva, the Vedas, the temple, karma and key festivals — described the way Hindus understand and teach them.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Brahman': 'The single, ultimate reality that Hindus believe underlies and connects the whole universe.',
                'Vishnu': 'A widely worshipped deity, believed by Hindus to preserve and protect the universe.',
                'Shiva': 'A widely worshipped deity, associated by Hindus with change and transformation.',
                'The Vedas': 'A collection of ancient sacred texts, among the oldest in Hinduism.',
                'Mandir': 'The Hindu word for a temple, where Hindus worship and make offerings.',
                'Diwali': 'The festival of lights, celebrated by Hindus to mark good triumphing over evil.',
                'Reincarnation': 'The belief that after death, a soul is reborn into a new body.',
                'Karma': 'The belief that a person\'s actions affect what happens to them in this life and future lives.',
                'Om': 'A sacred sound and symbol used by Hindus in prayer and meditation.',
                'Puja': 'An act of worship, often performed at a home shrine or in a temple.',
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
                storageKey: 'hinduismBasicsGame.settings',
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
                        questionText: "What is '" + term + "' in Hinduism?",
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about whether this is a belief, a deity, a text, a place or a festival.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered the basics of Hinduism!",
            });
        })();
    </script>
@endpush
