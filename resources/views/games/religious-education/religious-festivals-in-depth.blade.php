@extends('layouts.app')

@section('meta_title', 'Religious Festivals in Depth — Religious Education Game for Kids')
@section('meta_blurb', 'A free religious education game exploring the customs, food and traditions behind major festivals across different religions.')
@section('meta_words', 'religious festivals in depth game, religious education game, ks3 re game, diwali eid hanukkah passover customs quiz')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-calendar-week',
        'title' => 'Religious Festivals in Depth',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Festival customs'],
        ],
        'aboutTitle' => 'About this religious festivals in depth game',
        'aboutText' => 'This free religious education game looks more closely at the customs, food and traditions behind major festivals across different religions — from lighting the menorah at Hanukkah to the Seder meal at Passover.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Christmas': 'Christians mark it with nativity plays, giving gifts, and decorating trees and homes.',
                'Easter': 'Christians often precede it with a period of fasting called Lent, and mark it with decorated eggs symbolising new life.',
                'Diwali': 'Hindus light small oil lamps called diyas, decorate their homes, share sweets, and enjoy fireworks.',
                'Eid al-Fitr': 'Muslims attend special prayers, wear new clothes, give to charity, and share festive meals with family.',
                'Eid al-Adha': 'Muslims remember Ibrahim\'s willingness to obey God, and often share meat from a sacrificed animal with those in need.',
                'Hanukkah': 'Jewish families light one candle on the menorah each night for eight nights, eat fried foods like latkes, and play with a spinning top called a dreidel.',
                'Passover': 'Jewish families hold a special meal called a Seder and eat unleavened bread called matzah.',
                'Vaisakhi': 'Sikhs take part in processions called Nagar Kirtan and remember the founding of the Khalsa.',
                'Wesak (Buddha Day)': 'Buddhists decorate temples and homes with lanterns and take part in acts of charity.',
                'Navratri': 'Hindus take part in nine nights of dance, including garba and dandiya, and often fast during the day.',
                'Rosh Hashanah': 'Jewish people blow a ram\'s horn called the shofar and eat apples dipped in honey for a sweet new year.',
                'Guru Nanak Gurpurab': 'Sikhs hold a continuous reading of the Guru Granth Sahib called Akhand Path and take part in processions.',
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
                storageKey: 'religiousFestivalsInDepthGame.settings',
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
                        questionText: "Which customs and traditions belong to " + term + "?",
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about the food, objects and activities linked to this festival.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You're a festival customs superstar!",
            });
        })();
    </script>
@endpush
