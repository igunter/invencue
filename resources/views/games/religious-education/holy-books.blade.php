@extends('layouts.app')

@section('meta_title', 'Special & Holy Books — Religious Education Game for Kids')
@section('meta_blurb', 'A free religious education game for kids — match holy books like the Bible, Quran, Torah and Guru Granth Sahib to their religion.')
@section('meta_words', 'holy books game, religious education game for kids, ks2 re game, bible quran torah guru granth sahib vedas quiz')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-book',
        'title' => 'Special & Holy Books',
        'subtitle' => 'Read the clue, then work out the religion!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Holy books'],
        ],
        'aboutTitle' => 'About this holy books game',
        'aboutText' => 'This free religious education game teaches young children about important religious texts — the Bible, the Quran, the Torah, the Guru Granth Sahib and more — and which religion each one belongs to.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const RELIGIONS = ['Christianity', 'Islam', 'Judaism', 'Hinduism', 'Buddhism', 'Sikhism'];

            const FACTS = [
                { q: "The Bible is the holy book of which religion?", a: "Christianity" },
                { q: "The Quran is the holy book of which religion?", a: "Islam" },
                { q: "The Torah is the most sacred text of which religion?", a: "Judaism" },
                { q: "The Guru Granth Sahib is the holy book of which religion?", a: "Sikhism" },
                { q: "The Vedas are a collection of ancient sacred texts in which religion?", a: "Hinduism" },
                { q: "The Tripitaka is a collection of important teachings in which religion?", a: "Buddhism" },
                { q: "The Bhagavad Gita is an important sacred text in which religion?", a: "Hinduism" },
                { q: "The New Testament, which tells the story of Jesus, forms part of the holy book of which religion?", a: "Christianity" },
                { q: "The Talmud, a collection of teachings and commentary, is an important text in which religion?", a: "Judaism" },
                { q: "The Hadith, collections of the sayings and actions of the Prophet Muhammad, are important in which religion?", a: "Islam" },
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
                storageKey: 'holyBooksGame.settings',
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
                    return 'Think about which religion you have heard using this book.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a holy books superstar!",
            });
        })();
    </script>
@endpush
