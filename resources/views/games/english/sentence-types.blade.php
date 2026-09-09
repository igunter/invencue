@extends('layouts.app')

@section('meta_title', 'Sentence Types — English Game for Kids')
@section('meta_blurb', 'A free English game for kids — decide whether a sentence is a statement, question, command or exclamation.')
@section('meta_words', 'sentence types game, statement question command exclamation, ks2 english game, ks3 english game, grammar game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-chat-square-text',
        'title' => 'Sentence Types',
        'subtitle' => 'Read the sentence, then say what type it is!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Sentence types'],
        ],
        'aboutTitle' => 'About this sentence types game',
        'aboutText' => 'This free English game tests the four sentence types — statements, questions, commands and exclamations — and the punctuation and tone that go with each one.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const SENTENCES = {
                'I like chocolate ice cream.': 'Statement',
                'What time is it?': 'Question',
                'Close the door, please.': 'Command',
                'What a fantastic goal that was!': 'Exclamation',
                'The train leaves at nine o\'clock.': 'Statement',
                'Where did you put my book?': 'Question',
                'Sit down and be quiet.': 'Command',
                'I can\'t believe we won!': 'Exclamation',
                'London is the capital of England.': 'Statement',
                'Have you finished your homework?': 'Question',
                'Pass the salt, please.': 'Command',
                'That was the scariest film ever!': 'Exclamation',
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
                storageKey: 'sentence-typesGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const sentence = SENTENCE_NAMES[randInt(0, SENTENCE_NAMES.length - 1)];
                    return {
                        category: type,
                        correctText: SENTENCES[sentence],
                        questionText: "'" + sentence + "' — what type of sentence is this?",
                    };
                },

                buildChoices: function() {
                    return shuffle(['Statement', 'Question', 'Command', 'Exclamation']);
                },

                hintFor: function() {
                    return 'Look at the punctuation mark at the end, and think about what the sentence is doing.';
                },

                explanationFor: function(q) {
                    return "It's a " + q.correctText.toLowerCase() + '.';
                },

                masteryMessage: "Amazing! You're a sentence types superstar!",
            });
        })();
    </script>
@endpush
