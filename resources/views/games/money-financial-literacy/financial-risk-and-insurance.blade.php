@extends('layouts.app')

@section('meta_title', 'Financial Risk & Insurance — GCSE Financial Literacy Game')
@section('meta_blurb', 'A free GCSE financial literacy game covering financial risk and insurance — what insurance is for and basic risk management.')
@section('meta_words', 'financial risk and insurance game, insurance explained, risk management, gcse financial literacy game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-shield-check',
        'title' => 'Financial Risk & Insurance',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Risk & insurance facts'],
        ],
        'aboutTitle' => 'About this financial risk & insurance game',
        'aboutText' => 'This free GCSE financial literacy game covers what insurance is for, common types of insurance, and basic ideas about managing financial risk. Choose your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What is 'insurance'?", a: "An agreement where you pay regularly so a company covers you if something goes wrong" },
                { q: "What is a 'premium' in insurance?", a: "The regular amount you pay for an insurance policy" },
                { q: "Why do people take out car insurance?", a: "To cover the cost of accidents, damage or theft involving their car" },
                { q: "What is 'home insurance' used for?", a: "To cover the cost of damage, theft or loss affecting your home and belongings" },
                { q: "What is 'financial risk'?", a: "The chance of losing money or facing an unexpected cost" },
                { q: "Why might someone take out travel insurance before a holiday?", a: "To cover unexpected costs like medical care, cancellations or lost luggage" },
                { q: "What is an 'excess' in an insurance policy?", a: "The amount you agree to pay yourself before the insurance covers the rest" },
                { q: "Why do insurance companies charge different premiums to different people?", a: "They assess the level of risk — higher risk usually means a higher premium" },
                { q: "What is the general idea behind insurance?", a: "Sharing risk — many people pay in, so those who have a claim are covered" },
                { q: "Why might it be risky not to have insurance for something valuable, like a car?", a: "You'd have to pay the full cost yourself if something went wrong" },
                { q: "What is 'risk management' in personal finance?", a: "Taking steps to reduce or prepare for possible financial losses" },
                { q: "Give an example of a way to manage financial risk without buying insurance.", a: "Building up savings as an emergency fund" },
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
                storageKey: 'financialRiskAndInsuranceGame.settings',
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
                    return 'Think about how insurance shares out risk between many people who pay in.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You understand financial risk and insurance really well!",
            });
        })();
    </script>
@endpush
