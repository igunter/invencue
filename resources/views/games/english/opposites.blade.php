@extends('layouts.app')

@section('meta_title', 'Opposites (Antonyms) — English Game for Kids')
@section('meta_blurb', 'A free English game for kids — pick the word that means the opposite.')
@section('meta_words', 'opposites game, antonyms game for kids, vocabulary game, ks1 english game, ks2 english game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-arrow-left-right',
        'title' => 'Opposites (Antonyms)',
        'subtitle' => 'Read the word, then pick its opposite!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Opposites'],
        ],
        'aboutTitle' => 'About this opposites game',
        'aboutText' => 'This free English game helps young kids build vocabulary by matching everyday words to their opposites, such as "hot" and "cold" or "big" and "small".',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const OPPOSITES = {
                'hot': 'Cold',
                'big': 'Small',
                'up': 'Down',
                'fast': 'Slow',
                'happy': 'Sad',
                'day': 'Night',
                'wet': 'Dry',
                'old': 'New',
                'open': 'Closed',
                'full': 'Empty',
                'light': 'Dark',
                'hard': 'Soft',
            };
            const WORD_NAMES = Object.keys(OPPOSITES);

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
                storageKey: 'opposites-antonymsGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const word = WORD_NAMES[randInt(0, WORD_NAMES.length - 1)];
                    return {
                        category: type,
                        label: word,
                        correctText: OPPOSITES[word],
                        questionText: "What is the opposite of '" + word + "'?",
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(WORD_NAMES, q.label, 3).map(function(w) { return OPPOSITES[w]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think of a word that means the complete opposite.';
                },

                explanationFor: function(q) {
                    return 'The opposite of "' + q.label + '" is "' + q.correctText + '".';
                },

                masteryMessage: "Amazing! You're an opposites superstar!",
            });
        })();
    </script>
@endpush
