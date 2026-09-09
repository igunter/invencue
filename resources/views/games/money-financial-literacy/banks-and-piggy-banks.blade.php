@extends('layouts.app')

@section('meta_title', 'Banks & Piggy Banks — Money Game for Kids')
@section('meta_blurb', 'A free money game for young kids covering what a bank is and does, and how it compares to a piggy bank at home.')
@section('meta_words', 'banks game for kids, piggy bank game, what is a bank, kids money game, ks1 money game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-bank',
        'title' => 'Banks & Piggy Banks',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Bank facts'],
        ],
        'aboutTitle' => 'About this banks & piggy banks game',
        'aboutText' => 'This free money game gives young kids simple facts about what a bank is and does, and how it compares to keeping coins in a piggy bank at home. Choose your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What is a bank?", a: "A safe place where people keep their money" },
                { q: "What is a piggy bank used for?", a: "Keeping coins and notes safe at home" },
                { q: "What do people use to take money out of a cash machine?", a: "A bank card" },
                { q: "What is it called when you put money into a bank account?", a: "Making a deposit / paying money in" },
                { q: "Why might someone keep their money in a bank instead of at home?", a: "It's safer and can be looked after properly" },
                { q: "What is a cash machine (ATM) used for?", a: "Taking out cash from your bank account" },
                { q: "What might a bank give you to help you save, alongside a piggy bank at home?", a: "A savings account" },
                { q: "What is it called when you take money out of your account?", a: "A withdrawal" },
                { q: "Is it safe to leave a lot of cash lying around at home?", a: "No — it's safer to keep most money in a bank" },
                { q: "What can a bank do with the money people save with them?", a: "Look after it safely and sometimes pay a little extra for saving with them" },
                { q: "Why do many families use a bank as well as a piggy bank at home?", a: "A bank keeps larger amounts of money safe and secure" },
                { q: "What is the record of money going in and out of a bank account called?", a: "A statement" },
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
                storageKey: 'banksAndPiggyBanksGame.settings',
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
                    return 'Think about how a bank keeps money safe, compared to keeping coins at home.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You know all about banks and piggy banks!",
            });
        })();
    </script>
@endpush
