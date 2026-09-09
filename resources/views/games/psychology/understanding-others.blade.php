@extends('layouts.app')

@section('meta_title', 'Understanding Others — Psychology Game for Kids')
@section('meta_blurb', 'A free psychology game for kids — simple empathy and perspective-taking scenarios.')
@section('meta_words', 'understanding others game for kids, empathy game, perspective taking, social skills game, psychology game for kids')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-people',
        'title' => 'Understanding Others',
        'subtitle' => 'Read the clue, then pick the caring answer!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Understanding others'],
        ],
        'aboutTitle' => 'About this understanding others game',
        'aboutText' => "This free game helps young kids practise empathy and seeing things from another person's point of view, using simple everyday scenarios.",
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What is it called when you try to imagine how someone else is feeling?", a: "Empathy" },
                { q: "Your friend is crying because they lost their pet. What's an empathetic thing to do?", a: "Sit with them and show you care" },
                { q: "Why might someone act grumpy even if they're not really angry at you?", a: "They might be having a hard day for another reason" },
                { q: "What does it mean to 'see things from someone else's point of view'?", a: "Perspective-taking" },
                { q: "Your classmate is struggling with a task you find easy. What's a kind response?", a: "Offer to help them without making them feel bad" },
                { q: "Why is it helpful to ask 'how are you feeling?' before deciding how to react to someone?", a: "It helps you understand what they're really going through" },
                { q: "A friend seems quiet and doesn't want to talk today. What's a caring response?", a: "Give them space but let them know you're there if needed" },
                { q: "Why might two people feel differently about the exact same event?", a: "Everyone experiences and feels things in their own way" },
                { q: "What's it called when you notice someone is upset just from their face or actions?", a: "Reading how someone feels" },
                { q: "Your friend is nervous about a test you find easy. What's a supportive thing to say?", a: "It's OK to feel nervous — you've got this" },
                { q: "Why is listening carefully important when a friend is telling you something important?", a: "It shows them you care about how they feel" },
                { q: "What should you do if you're not sure why a friend seems upset?", a: "Gently ask them if they're OK" },
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
                storageKey: 'understandingOthersGame.settings',
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
                    return 'Imagine how you would feel if you were in their shoes.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're brilliant at understanding others!",
            });
        })();
    </script>
@endpush
