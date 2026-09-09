@extends('layouts.app')

@section('meta_title', 'The Five Senses & Perception — Psychology Game for Kids')
@section('meta_blurb', 'A free psychology game covering the five senses and how the brain interprets sensory information.')
@section('meta_words', 'five senses game, perception game, optical illusions, how the brain sees, psychology game for kids')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-eye',
        'title' => 'The Five Senses & Perception',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Senses & perception'],
        ],
        'aboutTitle' => 'About this five senses & perception game',
        'aboutText' => 'This free game covers the five senses and perception — how the brain takes in raw sensory information and interprets it, including how optical illusions can trick the brain.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What is it called when your brain interprets information from your senses?", a: "Perception" },
                { q: "Which sense organ detects light and lets you see?", a: "Eyes" },
                { q: "Which sense organ detects sound waves?", a: "Ears" },
                { q: "What is it called when your eyes and brain get tricked into seeing something that isn't quite real?", a: "An optical illusion" },
                { q: "Which part of the brain mainly processes information from your eyes?", a: "The occipital lobe" },
                { q: "Why can two people sometimes perceive the same picture differently?", a: "Their brains interpret the same information in different ways" },
                { q: "What sense lets you detect flavours like sweet, salty and sour?", a: "Taste" },
                { q: "What sense lets you feel pressure, texture and temperature?", a: "Touch" },
                { q: "Which sense is closely linked to taste and helps you detect flavours through your nose?", a: "Smell" },
                { q: "What is it called when your brain fills in missing information to make sense of what you see?", a: "Perceptual filling-in" },
                { q: "Why might someone 'see' a face in random shapes, like clouds?", a: "The brain looks for familiar patterns, like faces" },
                { q: "What term describes the raw information your senses pick up, before your brain interprets it?", a: "Sensation" },
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
                storageKey: 'fiveSensesPerceptionGame.settings',
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
                    return 'Think about how your brain takes raw information from your senses and makes sense of it.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You understand the senses and perception!",
            });
        })();
    </script>
@endpush
