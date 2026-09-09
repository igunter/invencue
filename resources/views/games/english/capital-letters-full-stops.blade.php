@extends('layouts.app')

@section('meta_title', 'Capital Letters & Full Stops — English Game for Kids')
@section('meta_blurb', 'A free English game for kids — spot whether a sentence uses capital letters and full stops correctly.')
@section('meta_words', 'capital letters game, full stops game for kids, punctuation game, ks1 english game, sentence punctuation')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-type',
        'title' => 'Capital Letters & Full Stops',
        'subtitle' => 'Read the sentence, then say if it\'s punctuated correctly!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Correct or incorrect?'],
        ],
        'aboutTitle' => 'About this capital letters & full stops game',
        'aboutText' => 'This free English game helps young kids spot whether a sentence starts with a capital letter and ends with a full stop, building the foundations of correct sentence punctuation.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const SENTENCES = {
                'the cat sat on the mat.': 'Incorrect',
                'The cat sat on the mat.': 'Correct',
                'My name is Sam': 'Incorrect',
                'My name is Sam.': 'Correct',
                'we went to the park today.': 'Incorrect',
                'We went to the park today.': 'Correct',
                'she likes reading books.': 'Incorrect',
                'She likes reading books.': 'Correct',
                'It was a sunny day': 'Incorrect',
                'It was a sunny day.': 'Correct',
                'london is the capital of england.': 'Incorrect',
                'London is the capital of England.': 'Correct',
            };
            const SENTENCE_NAMES = Object.keys(SENTENCES);

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
                storageKey: 'capital-letters-full-stopsGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const sentence = SENTENCE_NAMES[randInt(0, SENTENCE_NAMES.length - 1)];
                    return {
                        category: type,
                        correctText: SENTENCES[sentence],
                        questionText: "'" + sentence + "' — is this sentence written correctly?",
                    };
                },

                buildChoices: function() {
                    return shuffle(['Correct', 'Incorrect']);
                },

                hintFor: function() {
                    return 'Check the very first letter and the very last mark of the sentence.';
                },

                explanationFor: function(q) {
                    return q.correctText === 'Correct'
                        ? 'It starts with a capital letter and ends with a full stop.'
                        : 'It is missing a capital letter, a full stop, or both.';
                },

                masteryMessage: "Amazing! You're a punctuation superstar!",
            });
        })();
    </script>
@endpush
