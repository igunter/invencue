@extends('layouts.app')

@section('meta_title', 'Religious Leaders & Teachers — Religious Education Game for Kids')
@section('meta_blurb', 'A free religious education game for kids — learn about religious leaders and teachers like a Pope, an Imam and a Rabbi, and what they do.')
@section('meta_words', 'religious leaders game, religious education game for kids, ks2 re game, pope imam rabbi guru monk quiz')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-people',
        'title' => 'Religious Leaders & Teachers',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Leaders & teachers'],
        ],
        'aboutTitle' => 'About this religious leaders & teachers game',
        'aboutText' => 'This free religious education game introduces young children to well-known religious leaders and teachers — such as a Pope, an Imam, a Rabbi and a Guru — and explains the role each one plays.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What is the title of the leader of the Roman Catholic Church?", a: "The Pope" },
                { q: "What do we call the Christian leader who leads worship and looks after a local church?", a: "A vicar or priest" },
                { q: "What do we call a Muslim leader who leads prayers in a mosque?", a: "An imam" },
                { q: "What do we call a Jewish religious teacher and leader?", a: "A rabbi" },
                { q: "What do we call a senior Christian leader who oversees a group of churches?", a: "A bishop" },
                { q: "What do we call a respected spiritual teacher, an important role in Sikhism and Hinduism?", a: "A guru" },
                { q: "What do we call a man who lives a religious life of prayer and study in a monastery?", a: "A monk" },
                { q: "What do we call a woman who lives a religious life of prayer and study in a convent or monastery?", a: "A nun" },
                { q: "What is the title of an important spiritual leader in Tibetan Buddhism?", a: "The Dalai Lama" },
                { q: "What do we call the person who reads and looks after the Guru Granth Sahib in a gurdwara?", a: "A granthi" },
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
                storageKey: 'religiousLeadersGame.settings',
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
                    return 'Think about which religion this leader or teacher belongs to, and what job they do.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a religious leaders superstar!",
            });
        })();
    </script>
@endpush
