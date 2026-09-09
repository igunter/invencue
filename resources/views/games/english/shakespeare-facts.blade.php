@extends('layouts.app')

@section('meta_title', 'Shakespeare Facts — GCSE English Game')
@section('meta_blurb', 'A free GCSE English game covering William Shakespeare\'s life, plays and famous works.')
@section('meta_words', 'shakespeare facts game, gcse english game, shakespeare quiz, macbeth hamlet romeo and juliet, elizabethan theatre')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-mask',
        'title' => 'Shakespeare Facts',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Shakespeare facts'],
        ],
        'aboutTitle' => 'About this Shakespeare facts game',
        'aboutText' => 'This free GCSE English game covers key facts about William Shakespeare\'s life and work — where he was born, the Globe Theatre, his most famous plays, and the era he lived and wrote in.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "In which English town was William Shakespeare born?", a: "Stratford-upon-Avon" },
                { q: "In which era did Shakespeare live and write?", a: "The Elizabethan era" },
                { q: "What is the name of the London theatre most associated with Shakespeare's plays?", a: "The Globe Theatre" },
                { q: "Which Shakespeare play features the characters Romeo and Juliet?", a: "Romeo and Juliet" },
                { q: "In Macbeth, what title does Macbeth hold at the very start of the play, before becoming king?", a: "Thane of Cawdor" },
                { q: "Which of Shakespeare's plays features a Danish prince who says 'To be, or not to be'?", a: "Hamlet" },
                { q: "What type of play is Shakespeare's 'A Midsummer Night's Dream'?", a: "A comedy" },
                { q: "What term describes plays like Hamlet, Macbeth and King Lear, which end in the death of the main character?", a: "A tragedy" },
                { q: "Who was the reigning monarch for much of Shakespeare's writing career?", a: "Queen Elizabeth I" },
                { q: "About how many plays is Shakespeare traditionally believed to have written?", a: "About 37" },
                { q: "What is the term for a 14-line poem, a form Shakespeare wrote 154 of?", a: "A sonnet" },
                { q: "In Shakespeare's play, which Scottish general is driven to murder King Duncan by prophecy and ambition?", a: "Macbeth" },
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
                storageKey: 'shakespeare-factsGame.settings',
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
                    return 'Think about Shakespeare\'s life, his most famous plays, and the theatre he worked in.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a Shakespeare superstar!",
            });
        })();
    </script>
@endpush
