@extends('layouts.app')

@section('meta_title', 'World Religions — Religious Education Game for Kids')
@section('meta_blurb', 'A free religious education game for kids — match symbols, books, places of worship and festivals to the world religion they belong to.')
@section('meta_words', 'world religions game, religious education game for kids, ks2 re game, christianity islam judaism hinduism buddhism sikhism quiz')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-globe2',
        'title' => 'World Religions',
        'subtitle' => 'Read the clue, then work out the religion!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'World religions'],
        ],
        'aboutTitle' => 'About this world religions game',
        'aboutText' => 'This free religious education game introduces young children to six major world religions — Christianity, Islam, Judaism, Hinduism, Buddhism and Sikhism — by matching well-known symbols, holy books, places of worship and festivals to the religion they belong to.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const RELIGIONS = ['Christianity', 'Islam', 'Judaism', 'Hinduism', 'Buddhism', 'Sikhism'];

            const FACTS = [
                { q: "Which religion's holy book is called the Bible?", a: "Christianity" },
                { q: "Which religion's holy book is called the Quran?", a: "Islam" },
                { q: "Which religion's holy book is called the Torah?", a: "Judaism" },
                { q: "Which religion teaches about a spiritual teacher known as the Buddha?", a: "Buddhism" },
                { q: "Which religion honours many deities, including Vishnu and Shiva?", a: "Hinduism" },
                { q: "Which religion was founded by Guru Nanak?", a: "Sikhism" },
                { q: "Which religion celebrates Christmas to remember the birth of Jesus?", a: "Christianity" },
                { q: "Which religion celebrates Eid al-Fitr at the end of the fasting month of Ramadan?", a: "Islam" },
                { q: "Which religion celebrates Hanukkah, the festival of lights?", a: "Judaism" },
                { q: "Which religion's place of worship is called a mosque?", a: "Islam" },
                { q: "Which religion's place of worship is called a synagogue?", a: "Judaism" },
                { q: "Which religion's place of worship is called a gurdwara?", a: "Sikhism" },
                { q: "Which religion's followers are called Christians?", a: "Christianity" },
                { q: "Which religion teaches that people can be reborn many times, an idea called reincarnation?", a: "Hinduism" },
                { q: "Which religion's holy book is called the Guru Granth Sahib?", a: "Sikhism" },
                { q: "Which religion teaches that Muhammad was chosen to receive God's message?", a: "Islam" },
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

            function pickOthers(pool, exclude, count) {
                return shuffle(pool.filter(function(x) { return x !== exclude; })).slice(0, count);
            }

            window.ScienceQuiz.run({
                storageKey: 'worldReligionsGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const item = FACTS[randInt(0, FACTS.length - 1)];
                    return { category: type, correctText: item.a, questionText: item.q };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(RELIGIONS, q.correctText, 3);
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about the symbols, books, buildings and festivals you know for each religion.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a world religions superstar!",
            });
        })();
    </script>
@endpush
