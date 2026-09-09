@extends('layouts.app')

@section('meta_title', 'Calming Down — Psychology Game for Kids')
@section('meta_blurb', 'A free psychology game for kids — simple strategies for managing big feelings, like breathing and counting.')
@section('meta_words', 'calming down game for kids, managing big feelings, breathing strategies, emotional regulation, psychology game for kids')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-wind',
        'title' => 'Calming Down',
        'subtitle' => 'Read the clue, then pick the calming strategy!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Calming down'],
        ],
        'aboutTitle' => 'About this calming down game',
        'aboutText' => 'This free game introduces young kids to simple, friendly strategies for managing big feelings — like taking slow breaths, counting to ten, and talking to someone they trust.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What can you do with your breathing to help you feel calmer?", a: "Take slow, deep breaths" },
                { q: "What is counting slowly to ten useful for?", a: "Helping you calm down before reacting" },
                { q: "Why can it help to talk to a trusted adult about a big feeling?", a: "They can help you understand and work through it" },
                { q: "What is a 'calm down spot' at school or home used for?", a: "A quiet place to relax when feelings feel too big" },
                { q: "Why might squeezing a stress ball or fidget toy help when you're upset?", a: "It gives your hands something calming to do" },
                { q: "What can going for a short walk or moving your body help with?", a: "Letting out big feelings and feeling calmer" },
                { q: "Why is it helpful to name the feeling you're having, like 'I feel angry'?", a: "It helps you understand and manage the feeling" },
                { q: "What should you try to do before shouting or hitting when you're angry?", a: "Stop and take a breath first" },
                { q: "Why can drawing or writing about a feeling help you feel better?", a: "It's a safe way to let the feeling out" },
                { q: "What is it called when you imagine a calm, happy place in your mind?", a: "Visualising somewhere calm" },
                { q: "Why might listening to calm music help when you're upset?", a: "It can help your body and mind relax" },
                { q: "What's a good first step if a big feeling starts to feel overwhelming?", a: "Pause and take a few slow breaths" },
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
                storageKey: 'calmingDownGame.settings',
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
                    return 'Think about ways to slow your body and mind down.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You know great ways to calm down!",
            });
        })();
    </script>
@endpush
