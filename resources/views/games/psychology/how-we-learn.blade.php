@extends('layouts.app')

@section('meta_title', 'How We Learn — Psychology Game for Kids')
@section('meta_blurb', 'A free psychology game for kids — simple facts about practice, repetition and trying new things.')
@section('meta_words', 'how we learn game for kids, learning skills game, practice and repetition, psychology game for kids')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-mortarboard',
        'title' => 'How We Learn',
        'subtitle' => 'Read the clue, then pick the best answer!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'How we learn'],
        ],
        'aboutTitle' => 'About this how we learn game',
        'aboutText' => 'This free game introduces young kids to simple ideas about learning — why practice, repetition, mistakes and trying new things all help your brain get better at something.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What helps your brain remember something better — doing it once, or practising it many times?", a: "Practising it many times" },
                { q: "What is it called when you learn something by watching someone else do it first?", a: "Learning by watching" },
                { q: "Why is it useful to make mistakes while you're learning something new?", a: "Mistakes show you what to try differently next time" },
                { q: "What can help you remember new information — repeating it, or hearing it just once?", a: "Repeating it" },
                { q: "What is it called when you try something new even though it feels hard?", a: "Taking on a challenge" },
                { q: "Why is it helpful to take breaks while you're learning something difficult?", a: "It gives your brain time to rest and recharge" },
                { q: "What usually helps you learn a new skill faster — lots of short practices, or one very long practice?", a: "Lots of short practices" },
                { q: "What is it called when you connect new information to something you already know?", a: "Making a connection" },
                { q: "Why is asking questions helpful when you're learning something new?", a: "It helps you understand things you're unsure about" },
                { q: "What happens in your brain when you practise a skill again and again?", a: "It builds a stronger pathway for that skill" },
                { q: "Why is it helpful to explain something to someone else after you've learned it?", a: "It helps you check how well you understand it" },
                { q: "What's a good way to learn a list of spellings?", a: "Practise them a little bit every day" },
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
                storageKey: 'howWeLearnGame.settings',
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
                    return 'Think about what helps information stick in your memory.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You know how learning works!",
            });
        })();
    </script>
@endpush
