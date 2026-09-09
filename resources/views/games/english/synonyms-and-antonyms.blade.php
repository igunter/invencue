@extends('layouts.app')

@section('meta_title', 'Synonyms & Antonyms — English Game for Kids')
@section('meta_blurb', 'A free English game for kids — find a word that means the same, or the opposite, as the word shown.')
@section('meta_words', 'synonyms game, antonyms game, vocabulary game for kids, ks2 english game, ks3 english game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-arrow-repeat',
        'title' => 'Synonyms & Antonyms',
        'subtitle' => 'Pick your question types, then match the meanings!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'synonyms', 'label' => 'Synonyms'],
            ['id' => 'antonyms', 'label' => 'Antonyms'],
        ],
        'aboutTitle' => 'About this synonyms & antonyms game',
        'aboutText' => 'This free English game builds vocabulary by asking you to find synonyms (words that mean the same) and antonyms (words that mean the opposite) for everyday words. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const SYNONYMS = {
                'happy': 'Joyful',
                'big': 'Large',
                'fast': 'Quick',
                'scared': 'Afraid',
                'angry': 'Furious',
                'tired': 'Exhausted',
                'smart': 'Clever',
                'small': 'Tiny',
                'funny': 'Hilarious',
                'sad': 'Unhappy',
                'brave': 'Courageous',
                'quiet': 'Silent',
            };
            const SYNONYM_NAMES = Object.keys(SYNONYMS);

            const ANTONYMS = {
                'hot': 'Cold',
                'happy': 'Sad',
                'fast': 'Slow',
                'big': 'Small',
                'light': 'Dark',
                'easy': 'Difficult',
                'brave': 'Cowardly',
                'full': 'Empty',
                'wide': 'Narrow',
                'strong': 'Weak',
                'loud': 'Quiet',
                'ancient': 'Modern',
            };
            const ANTONYM_NAMES = Object.keys(ANTONYMS);

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
                storageKey: 'synonyms-and-antonymsGame.settings',
                types: ['synonyms', 'antonyms'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'synonyms') {
                        const word = SYNONYM_NAMES[randInt(0, SYNONYM_NAMES.length - 1)];
                        return {
                            category: type,
                            label: word,
                            correctText: SYNONYMS[word],
                            questionText: "Which word means the same as '" + word + "'?",
                        };
                    }
                    const word = ANTONYM_NAMES[randInt(0, ANTONYM_NAMES.length - 1)];
                    return {
                        category: type,
                        label: word,
                        correctText: ANTONYMS[word],
                        questionText: "Which word means the opposite of '" + word + "'?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'synonyms') {
                        const distractors = pickOthers(SYNONYM_NAMES, q.label, 3).map(function(w) { return SYNONYMS[w]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(ANTONYM_NAMES, q.label, 3).map(function(w) { return ANTONYMS[w]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'synonyms') return 'Think of a word with a very similar meaning.';
                    return 'Think of a word with the completely opposite meaning.';
                },

                explanationFor: function(q) {
                    if (q.category === 'synonyms') return '"' + q.correctText + '" means the same as "' + q.label + '".';
                    return '"' + q.correctText + '" means the opposite of "' + q.label + '".';
                },

                masteryMessage: "Amazing! You're a synonyms and antonyms superstar!",
            });
        })();
    </script>
@endpush
