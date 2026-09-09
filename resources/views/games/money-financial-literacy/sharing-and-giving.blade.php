@extends('layouts.app')

@section('meta_title', 'Sharing & Giving — Money Game for Kids')
@section('meta_blurb', 'A free money game for young kids about sharing, giving to charity, and helping others with what we have.')
@section('meta_words', 'sharing and giving game, charity game for kids, kids money game, financial literacy for kids, ks1 money game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-heart',
        'title' => 'Sharing & Giving',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Sharing facts'],
        ],
        'aboutTitle' => 'About this sharing & giving game',
        'aboutText' => 'This free money game introduces young kids to the ideas of sharing and giving — helping others with money, time or belongings, and understanding what charities do. Choose your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What is it called when you give some of your money to help other people?", a: "Giving / donating" },
                { q: "What is a charity?", a: "An organisation that helps people, animals or causes in need" },
                { q: "What is it called when you give some of your pocket money to a charity?", a: "A donation" },
                { q: "Why might someone choose to share their money or belongings with others?", a: "To help people who have less, or to be kind" },
                { q: "What could you do with old toys you don't play with any more, instead of throwing them away?", a: "Give them to charity or to someone who needs them" },
                { q: "What is it called when a group of people each give a small amount to raise money together?", a: "Fundraising" },
                { q: "If a friend doesn't have enough for something, what is one kind thing you could do?", a: "Share what you have or help them" },
                { q: "What is one way children can raise money for charity?", a: "A sponsored event, like a walk or a bake sale" },
                { q: "Why is giving to others sometimes just as good as spending on yourself?", a: "It can help people in need and make you feel good too" },
                { q: "What do we call money or items given freely to help someone else?", a: "A gift or donation" },
                { q: "Is sharing only about money?", a: "No — you can also share your time, toys or kindness" },
                { q: "What might a school do to raise money for a good cause?", a: "Hold a charity day, like wearing non-uniform for a donation" },
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
                storageKey: 'sharingAndGivingGame.settings',
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
                    return 'Think about the different ways people can help others with money, time or belongings.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a real sharing superstar!",
            });
        })();
    </script>
@endpush
