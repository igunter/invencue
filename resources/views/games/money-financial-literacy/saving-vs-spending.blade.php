@extends('layouts.app')

@section('meta_title', 'Saving vs Spending — Money Game for Kids')
@section('meta_blurb', 'A free money game covering saving vs spending, including simple interest concepts and why saving can pay off.')
@section('meta_words', 'saving vs spending game, interest for kids, kids money game, financial literacy game, ks3 money game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-graph-up-arrow',
        'title' => 'Saving vs Spending',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Saving & spending facts'],
        ],
        'aboutTitle' => 'About this saving vs spending game',
        'aboutText' => 'This free money game explores the trade-offs between saving and spending, including how interest can help savings grow over time. Choose your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What is 'interest' when it comes to a savings account?", a: "Extra money a bank pays you for keeping your savings with them" },
                { q: "If you save money in a bank savings account, what can happen to it over time?", a: "It can grow a little, thanks to interest" },
                { q: "What is an opportunity cost of spending money now instead of saving it?", a: "You lose the chance to use that money for something else later" },
                { q: "Why might saving money be better than spending it all straight away?", a: "It lets you build up money for bigger things, and may earn interest" },
                { q: "What is the general idea of 'simple interest'?", a: "Interest calculated only on the original amount you saved" },
                { q: "Why do banks pay interest to people who save with them?", a: "To reward customers for keeping their money with the bank" },
                { q: "What is a trade-off you make when you decide to spend money on something now?", a: "You can't save or spend that same money on something else later" },
                { q: "What might happen to £10 left in a piggy bank for a year compared with £10 in a savings account?", a: "The £10 in a savings account may grow a little with interest, the piggy bank stays £10" },
                { q: "Why is starting to save early usually a good idea?", a: "The longer money is saved, the more time it has to grow" },
                { q: "What does it mean to 'delay gratification' when it comes to money?", a: "Waiting before you spend, so you can save up for something bigger" },
                { q: "Is spending money always a bad decision?", a: "No — spending sensibly is normal, it's about balancing spending and saving" },
                { q: "What could you compare before deciding whether to spend or save some money?", a: "What you'd gain from spending it now versus saving it for later" },
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
                storageKey: 'savingVsSpendingGame.settings',
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
                    return 'Think about what happens to money left saved over time, versus money spent straight away.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You understand saving and spending really well!",
            });
        })();
    </script>
@endpush
