@extends('layouts.app')

@section('meta_title', 'Friendship & Kindness — Psychology Game for Kids')
@section('meta_blurb', 'A free psychology game for kids — work out the kind, friendly thing to do in everyday situations.')
@section('meta_words', 'friendship game for kids, kindness game, being a good friend, social skills game, psychology game for kids')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-heart',
        'title' => 'Friendship & Kindness',
        'subtitle' => 'Read the clue, then pick the kind thing to do!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Friendship'],
        ],
        'aboutTitle' => 'About this friendship & kindness game',
        'aboutText' => 'This free game helps young kids think about what makes a good friend, practising kind and thoughtful responses to everyday friendship situations.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "Your friend is sitting alone at lunch looking sad. What's a kind thing to do?", a: "Sit with them and ask if they're OK" },
                { q: "A new student joins your class and doesn't know anyone. What's a kind way to welcome them?", a: "Invite them to play with you" },
                { q: "Your friend is upset because they lost a game. What should a good friend do?", a: "Comfort them and remind them it's just a game" },
                { q: "You want to join a game your friends are playing. What's the polite thing to do?", a: "Ask if you can join in" },
                { q: "Your friend shares their crayons with you even though they don't have many. What is this called?", a: "Being generous" },
                { q: "You accidentally bump into a friend and knock their bag over. What should you do?", a: "Say sorry and help pick it up" },
                { q: "Your friend tells you a secret. What should a trustworthy friend do?", a: "Keep the secret safe" },
                { q: "Two friends disagree about which game to play. What's a fair way to solve it?", a: "Take turns choosing the game" },
                { q: "Your friend is being teased by someone else. What's a kind thing to do?", a: "Stand up for them or get a teacher's help" },
                { q: "You notice a classmate dropped their pencil case and didn't see. What's a kind thing to do?", a: "Pick it up and give it back to them" },
                { q: "Your friend did something nice for you. What should you say?", a: "Thank you" },
                { q: "You and a friend both want the same toy. What's a good way to share it?", a: "Take turns playing with it" },
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
                storageKey: 'friendshipKindnessGame.settings',
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
                    return 'Think about what a caring friend would do.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a kindness superstar!",
            });
        })();
    </script>
@endpush
