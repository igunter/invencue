@extends('layouts.app')

@section('meta_title', 'Orchestra Layout & Roles — Music Game for Kids')
@section('meta_blurb', 'A free music game — learn the sections of an orchestra, seating layout, and what the conductor and leader do.')
@section('meta_words', 'orchestra layout game, orchestra sections quiz, conductor role, ks3 music game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-people',
        'title' => 'Orchestra Layout & Roles',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Orchestra facts'],
        ],
        'aboutTitle' => 'About this orchestra layout & roles game',
        'aboutText' => 'This free music game covers how an orchestra is organised, including the string, woodwind, brass and percussion sections, and the roles of the conductor and section leaders.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What is the job of the conductor in an orchestra?", a: "To lead the orchestra and keep everyone playing together" },
                { q: "Which section of the orchestra usually sits at the front, closest to the conductor?", a: "The strings" },
                { q: "Which orchestral section includes flutes, oboes, clarinets and bassoons?", a: "The woodwind section" },
                { q: "Which orchestral section includes trumpets, trombones, French horns and tubas?", a: "The brass section" },
                { q: "Which orchestral section includes timpani, cymbals and the bass drum?", a: "The percussion section" },
                { q: "Who is the leader of the first violins, usually sitting closest to the conductor?", a: "The leader (concertmaster)" },
                { q: "What object does a conductor often hold to help beat time?", a: "A baton" },
                { q: "Which family of instruments usually has the most players in an orchestra?", a: "The strings" },
                { q: "What do we call the printed music that each orchestra member reads from?", a: "A score" },
                { q: "Why do orchestra members tune their instruments together before a concert?", a: "To make sure they are all playing in tune with each other" },
                { q: "Which instrument usually gives the tuning note before a concert starts?", a: "The oboe" },
                { q: "What do we call a small group of musicians playing without a conductor, like a string quartet?", a: "A chamber ensemble" },
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
                storageKey: 'orchestraLayoutRolesGame.settings',
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
                    return 'Think about where each section sits, and who leads or organises the orchestra.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're an orchestra layout and roles superstar!",
            });
        })();
    </script>
@endpush
