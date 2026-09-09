@extends('layouts.app')

@section('meta_title', 'Comparative Religion — GCSE Religious Studies Game')
@section('meta_blurb', 'A free GCSE Religious Studies game exploring similarities and differences between religions — worship, holy books, afterlife beliefs and more.')
@section('meta_words', 'comparative religion game, gcse religious studies game, world religions comparison quiz, aqa edexcel re')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-diagram-3',
        'title' => 'Comparative Religion',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Comparative religion'],
        ],
        'aboutTitle' => 'About this comparative religion game',
        'aboutText' => 'This free GCSE Religious Studies game explores similarities and differences between Christianity, Islam, Judaism, Hinduism, Buddhism and Sikhism — comparing their beliefs about the afterlife, worship, holy books and places of worship at GCSE level.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "Which two religions teach a belief in reincarnation, being reborn into a new life after death?", a: "Hinduism and Buddhism" },
                { q: "Which religion teaches belief in one God who revealed the Quran to the Prophet Muhammad?", a: "Islam" },
                { q: "Which religion teaches belief in the Trinity — God as Father, Son and Holy Spirit?", a: "Christianity" },
                { q: "Which two religions both trace their belief in one God back to the covenant made with Abraham?", a: "Judaism and Christianity" },
                { q: "Which religion's followers gather to pray in a synagogue, led by a rabbi?", a: "Judaism" },
                { q: "Which religion's followers pray five times a day facing towards Makkah?", a: "Islam" },
                { q: "Which religion regards the Guru Granth Sahib as its living, eternal Guru?", a: "Sikhism" },
                { q: "Which religion aims for a state called nirvana, the end of suffering, through the Eightfold Path?", a: "Buddhism" },
                { q: "Which religion worships a wide range of deities, including Vishnu and Shiva, seen as forms of one ultimate reality?", a: "Hinduism" },
                { q: "Which religion's central festival, Easter, celebrates the resurrection of its founder?", a: "Christianity" },
                { q: "Which religion serves a free communal meal called langar at its place of worship?", a: "Sikhism" },
                { q: "Which two Abrahamic religions both regard Jerusalem as an important holy city?", a: "Judaism and Christianity" },
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
                storageKey: 'comparativeReligionGame.settings',
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
                    return 'Think about which religion, or religions, hold this belief or practice.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You've mastered comparative religion!",
            });
        })();
    </script>
@endpush
