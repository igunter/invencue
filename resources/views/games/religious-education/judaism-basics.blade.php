@extends('layouts.app')

@section('meta_title', 'Judaism Basics — Religious Education Game for Kids')
@section('meta_blurb', 'A free religious education game covering the basics of Judaism — God, the Torah, the synagogue and key festivals.')
@section('meta_words', 'judaism basics game, religious education game, ks3 re game, torah synagogue rabbi shabbat quiz')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-journal-text',
        'title' => 'Judaism Basics',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Judaism basics'],
        ],
        'aboutTitle' => 'About this Judaism basics game',
        'aboutText' => 'This free religious education game covers the key beliefs, figures and practices of Judaism — God, the Torah, the synagogue, Shabbat and key festivals — described the way Jewish people understand and teach them.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'God': 'The one being Jewish people worship and believe created the universe.',
                'The Torah': "Judaism's most sacred text, containing the first five books of the Hebrew Bible.",
                'Synagogue': 'The building where Jewish people gather to worship and study.',
                'Rabbi': 'A Jewish religious teacher and leader.',
                'The Ten Commandments': 'A set of rules that, according to the Torah, God gave to Moses on Mount Sinai.',
                'Shabbat': 'The Jewish day of rest, kept from Friday evening to Saturday evening.',
                'Passover': 'A festival that remembers the story of the Jewish people\'s escape from slavery in Egypt.',
                'Hanukkah': 'The eight-day festival of lights, remembering the rededication of the Temple in Jerusalem.',
                'Kosher': 'A term describing food that follows Jewish dietary laws.',
                'Star of David': 'A six-pointed star that is a widely recognised symbol of Judaism.',
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
                storageKey: 'judaismBasicsGame.settings',
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
                        questionText: "What is '" + term + "' in Judaism?",
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about whether this is a person, a book, a building, a day or a festival.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered the basics of Judaism!",
            });
        })();
    </script>
@endpush
