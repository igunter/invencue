@extends('layouts.app')

@section('meta_title', 'Religious Festivals — Religious Education Game for Kids')
@section('meta_blurb', 'A free religious education game for kids — learn what Christmas, Diwali, Eid, Hanukkah and other festivals celebrate.')
@section('meta_words', 'religious festivals game, religious education game for kids, ks2 re game, christmas diwali eid hanukkah quiz')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-calendar-event',
        'title' => 'Religious Festivals',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Festivals'],
        ],
        'aboutTitle' => 'About this religious festivals game',
        'aboutText' => 'This free religious education game teaches young children what festivals such as Christmas, Diwali, Eid al-Fitr, Hanukkah and Vaisakhi celebrate, in simple, straightforward language.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What do Christians celebrate at Christmas?", a: "The birth of Jesus" },
                { q: "What do Christians remember at Easter?", a: "The death and resurrection of Jesus" },
                { q: "What does Diwali celebrate?", a: "Good winning over evil, with lights, lamps and sweets" },
                { q: "What do Muslims celebrate at Eid al-Fitr?", a: "The end of the fasting month of Ramadan" },
                { q: "What do Muslims remember at Eid al-Adha?", a: "Ibrahim's willingness to obey God" },
                { q: "What does Hanukkah celebrate?", a: "The festival of lights, remembered by lighting a candle each night for eight nights" },
                { q: "What does Vaisakhi celebrate for Sikhs?", a: "The founding of the Sikh community known as the Khalsa" },
                { q: "What do Jewish families remember at Passover?", a: "The story of their ancestors' journey out of slavery in Egypt" },
                { q: "What do Buddhists celebrate at Wesak?", a: "The birth, enlightenment and death of the Buddha" },
                { q: "What does Navratri celebrate for Hindus?", a: "Nine nights of worship, dance and celebration" },
                { q: "What does Rosh Hashanah mark for Jewish people?", a: "The start of the Jewish New Year" },
                { q: "What does Guru Nanak Gurpurab celebrate for Sikhs?", a: "The birthday of Guru Nanak, the founder of Sikhism" },
            ];

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
                storageKey: 'religiousFestivalsGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const item = FACTS[randInt(0, FACTS.length - 1)];
                    return { category: type, correctText: item.a, questionText: item.q };
                },

                buildChoices: function(q) {
                    const distractors = shuffle(
                        FACTS.map(function(f) { return f.a; }).filter(function(a) { return a !== q.correctText; })
                    ).slice(0, 3);
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about who celebrates this festival and what the celebration is about.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a religious festivals superstar!",
            });
        })();
    </script>
@endpush
