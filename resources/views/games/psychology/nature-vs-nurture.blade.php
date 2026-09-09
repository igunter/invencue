@extends('layouts.app')

@section('meta_title', 'Nature vs Nurture — Psychology Game for Kids')
@section('meta_blurb', 'A free psychology game covering simple examples of genetics and environment shaping behaviour.')
@section('meta_words', 'nature vs nurture game, genetics and environment, psychology game for kids')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-flower2',
        'title' => 'Nature vs Nurture',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Nature vs nurture'],
        ],
        'aboutTitle' => 'About this nature vs nurture game',
        'aboutText' => 'This free game introduces the nature vs nurture debate — whether behaviour and traits come more from the genes we inherit, or the environment and experiences that shape us.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What term describes traits and characteristics we inherit through our genes?", a: "Nature" },
                { q: "What term describes the influence of our environment and experiences on who we become?", a: "Nurture" },
                { q: "Eye colour is passed down from your parents through genes. Is this an example of nature or nurture?", a: "Nature" },
                { q: "Learning to speak a particular language because of the country you grew up in is an example of what?", a: "Nurture" },
                { q: "A person's height is strongly influenced by the genes they inherit. Is this nature or nurture?", a: "Nature" },
                { q: "A child picking up their family's habits and values while growing up is an example of what?", a: "Nurture" },
                { q: "What is the term for the debate about whether we're shaped more by genetics or environment?", a: "The nature vs nurture debate" },
                { q: "Most psychologists today believe behaviour is usually shaped by what combination?", a: "Both nature and nurture together" },
                { q: "A talent for music that runs in a family could be due to inherited genes, learning from family members, or what?", a: "A mix of both nature and nurture" },
                { q: "Twin studies are often used by psychologists to study which topic?", a: "The nature vs nurture debate" },
                { q: "Being naturally more anxious due to inherited genes is an example of what?", a: "Nature" },
                { q: "Developing a fear of dogs after being frightened by one as a child is an example of what?", a: "Nurture" },
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
                storageKey: 'natureVsNurtureGame.settings',
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
                    return 'Think about whether it comes from genes you inherit, or from your experiences and surroundings.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You understand nature vs nurture!",
            });
        })();
    </script>
@endpush
