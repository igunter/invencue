@extends('layouts.app')

@section('meta_title', 'Medicine Through Time — GCSE History Game')
@section('meta_blurb', 'A free GCSE-level history game covering medicine through time — key figures and discoveries.')
@section('meta_words', 'medicine through time game, gcse history game, history of medicine quiz, jenner fleming nightingale')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-heart-pulse',
        'title' => 'Medicine Through Time',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Medicine facts'],
        ],
        'aboutTitle' => 'About this medicine through time game',
        'aboutText' => 'This free history game covers key figures and discoveries in the history of medicine, from ancient Greece to the founding of the NHS.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "Which scientist developed the first successful vaccine, against smallpox, in 1796?", a: "Edward Jenner" },
                { q: "What term describes the theory that diseases are caused by tiny living organisms called germs?", a: "Germ theory" },
                { q: "Which nurse became famous for improving hygiene and care for soldiers during the Crimean War?", a: "Florence Nightingale" },
                { q: "Which Scottish scientist discovered penicillin in 1928?", a: "Alexander Fleming" },
                { q: "What is the name of the UK healthcare system founded in 1948 to provide free medical care?", a: "The NHS" },
                { q: "Which Ancient Greek physician is often called the \"father of medicine\"?", a: "Hippocrates" },
                { q: "Which French scientist proved that germs cause disease and developed pasteurisation?", a: "Louis Pasteur" },
                { q: "Which doctor traced a London cholera outbreak to a contaminated water pump in 1854?", a: "John Snow" },
                { q: "Which German physicist discovered X-rays in 1895?", a: "Wilhelm Röntgen" },
                { q: "What term describes drugs used to stop patients feeling pain during operations?", a: "Anaesthetics" },
                { q: "Which Hungarian doctor discovered that hand-washing dramatically reduced deaths from infection in hospitals?", a: "Ignaz Semmelweis" },
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
                storageKey: 'medicine-through-timeGame.settings',
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
                    return 'Think carefully about the topic and time period.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a medicine through time superstar!",
            });
        })();
    </script>
@endpush
