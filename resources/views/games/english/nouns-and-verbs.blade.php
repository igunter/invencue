@extends('layouts.app')

@section('meta_title', 'Nouns & Verbs — English Game for Kids')
@section('meta_blurb', 'A free English game for kids — decide whether a word is a noun, a verb or an adjective.')
@section('meta_words', 'nouns and verbs game, word classes game for kids, adjectives game, ks1 english game, parts of speech')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-fonts',
        'title' => 'Nouns & Verbs',
        'subtitle' => 'Read the word, then say what kind of word it is!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Nouns, verbs & adjectives'],
        ],
        'aboutTitle' => 'About this nouns & verbs game',
        'aboutText' => 'This free English game helps young kids tell the difference between nouns (naming words), verbs (doing words) and adjectives (describing words) — a key building block for understanding sentences.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const WORDS = {
                'dog': 'Noun',
                'jump': 'Verb',
                'happy': 'Adjective',
                'table': 'Noun',
                'run': 'Verb',
                'blue': 'Adjective',
                'teacher': 'Noun',
                'sing': 'Verb',
                'tall': 'Adjective',
                'book': 'Noun',
                'swim': 'Verb',
                'soft': 'Adjective',
            };
            const WORD_NAMES = Object.keys(WORDS);

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

            window.ScienceQuiz.run({
                storageKey: 'nouns-and-verbsGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const word = WORD_NAMES[randInt(0, WORD_NAMES.length - 1)];
                    return {
                        category: type,
                        correctText: WORDS[word],
                        questionText: "Is the word '" + word + "' a noun, a verb or an adjective?",
                    };
                },

                buildChoices: function() {
                    return shuffle(['Noun', 'Verb', 'Adjective']);
                },

                hintFor: function() {
                    return 'A noun names a person, place or thing. A verb is a doing word. An adjective describes a noun.';
                },

                explanationFor: function(q) {
                    return 'That word is a ' + q.correctText.toLowerCase() + '.';
                },

                masteryMessage: "Amazing! You're a nouns and verbs superstar!",
            });
        })();
    </script>
@endpush
