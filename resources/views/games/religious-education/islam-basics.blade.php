@extends('layouts.app')

@section('meta_title', 'Islam Basics — Religious Education Game for Kids')
@section('meta_blurb', 'A free religious education game covering the basics of Islam — Allah, the Prophet Muhammad, the Quran, the mosque and key practices.')
@section('meta_words', 'islam basics game, religious education game, ks3 re game, allah muhammad quran mosque quiz')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-compass',
        'title' => 'Islam Basics',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Islam basics'],
        ],
        'aboutTitle' => 'About this Islam basics game',
        'aboutText' => 'This free religious education game covers the key beliefs, figures and practices of Islam — Allah, the Prophet Muhammad, the Quran, the mosque, Ramadan and prayer — described the way Muslims understand and teach them.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Allah': 'The Arabic word Muslims use for God.',
                'The Prophet Muhammad': 'The final prophet, whom Muslims believe received God\'s message, recorded in the Quran.',
                'The Quran': "Islam's holy book, believed by Muslims to be the word of God as revealed to Muhammad.",
                'Mosque': 'The building where Muslims gather to pray.',
                'Ramadan': 'The month in the Islamic calendar during which Muslims fast from dawn until sunset.',
                'Eid al-Fitr': 'The festival that marks the end of the fasting month of Ramadan.',
                'Salah': 'The Islamic practice of praying five times a day, facing towards Makkah.',
                'Hajj': 'The pilgrimage to Makkah that Muslims who are able to should make at least once in their lifetime.',
                'Imam': 'A person who leads prayers in a mosque.',
                'Ummah': 'The worldwide community of Muslims.',
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
                storageKey: 'islamBasicsGame.settings',
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
                        questionText: "What is '" + term + "' in Islam?",
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about whether this is a person, a book, a building, a practice or a festival.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered the basics of Islam!",
            });
        })();
    </script>
@endpush
