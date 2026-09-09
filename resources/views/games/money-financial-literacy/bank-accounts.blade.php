@extends('layouts.app')

@section('meta_title', 'Bank Accounts — Money Game for Kids')
@section('meta_blurb', 'A free money game covering bank accounts — debit cards, current accounts and savings accounts basics.')
@section('meta_words', 'bank accounts game, debit card game, current account, savings account, kids money game, ks3 money game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-credit-card-2-front',
        'title' => 'Bank Accounts',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Bank account facts'],
        ],
        'aboutTitle' => 'About this bank accounts game',
        'aboutText' => 'This free money game covers the basics of bank accounts — debit cards, current accounts, savings accounts and how they work. Choose your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What is a current account mainly used for?", a: "Everyday spending, like paying bills and using a debit card" },
                { q: "What is a savings account mainly used for?", a: "Keeping money you don't need straight away, so it can grow" },
                { q: "What is a debit card?", a: "A card linked to your bank account that takes money straight from it when you spend" },
                { q: "How is a debit card different from a credit card?", a: "A debit card uses your own money; a credit card lets you borrow money to pay back later" },
                { q: "What is a PIN used for?", a: "To prove a card is being used by the right person" },
                { q: "What is a bank statement?", a: "A record showing money going in and out of your account" },
                { q: "What is a 'balance' in a bank account?", a: "The amount of money currently in the account" },
                { q: "Why might a savings account often pay more interest than a current account?", a: "It's designed for money you leave untouched for longer" },
                { q: "What is online banking?", a: "Managing your bank account using a website or app instead of visiting a branch" },
                { q: "Why is it important to keep your PIN secret?", a: "So nobody else can use your card to take your money" },
                { q: "What might happen if you try to spend more than the balance in your current account without agreement?", a: "The payment may be refused or you could be charged a fee" },
                { q: "Why might a young person open their first bank account?", a: "To safely keep and manage their own money, like pocket money or earnings" },
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
                storageKey: 'bankAccountsGame.settings',
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
                    return 'Think about the differences between everyday spending accounts and accounts for saving.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You know your way around bank accounts!",
            });
        })();
    </script>
@endpush
