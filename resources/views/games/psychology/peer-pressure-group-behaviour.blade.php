@extends('layouts.app')

@section('meta_title', 'Peer Pressure & Group Behaviour — Psychology Game for Kids')
@section('meta_blurb', 'A free psychology game covering peer pressure, conformity and group behaviour.')
@section('meta_words', 'peer pressure game, group behaviour game, conformity, social influence, psychology game for kids')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-people-fill',
        'title' => 'Peer Pressure & Group Behaviour',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Peer pressure'],
        ],
        'aboutTitle' => 'About this peer pressure & group behaviour game',
        'aboutText' => 'This free game introduces simple ideas about social influence — peer pressure, conformity, and how being part of a group can change what someone chooses to do.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What is it called when people your own age influence you to act a certain way?", a: "Peer pressure" },
                { q: "What is it called when peer pressure pushes you to do something you don't want to do?", a: "Negative peer pressure" },
                { q: "What is it called when friends encourage you to do something positive, like studying?", a: "Positive peer pressure" },
                { q: "What is it called when someone changes their opinion to match the group, even if they privately disagree?", a: "Conformity" },
                { q: "What is it called when people in a group take less individual responsibility for their actions?", a: "Diffusion of responsibility" },
                { q: "Why might someone go along with a group decision they don't actually agree with?", a: "To fit in or avoid standing out" },
                { q: "What's a helpful strategy if you feel pressured to do something you're not comfortable with?", a: "Say no confidently or suggest something else" },
                { q: "What is it called when a group of friends all start wearing or liking the same things?", a: "Conformity" },
                { q: "Why can having supportive friends help you resist negative peer pressure?", a: "They can back you up when you make a good choice" },
                { q: "What term describes doing something just because 'everyone else is doing it'?", a: "Peer pressure" },
                { q: "Why might a shy person feel braver about trying something new in a group?", a: "Group encouragement can boost confidence" },
                { q: "What's an example of positive group behaviour at school?", a: "Friends encouraging each other to do their homework" },
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
                storageKey: 'peerPressureGroupBehaviourGame.settings',
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
                    return 'Think about how being around other people can change what someone chooses to do.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You understand peer pressure and group behaviour!",
            });
        })();
    </script>
@endpush
