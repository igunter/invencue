@extends('layouts.app')

@section('meta_title', 'Consumer Rights — GCSE Financial Literacy Game')
@section('meta_blurb', 'A free GCSE financial literacy game covering basic UK consumer rights, including refunds for faulty goods.')
@section('meta_words', 'consumer rights game, uk consumer rights, refunds explained, consumer rights act, gcse financial literacy game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-award',
        'title' => 'Consumer Rights',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Consumer rights facts'],
        ],
        'aboutTitle' => 'About this consumer rights game',
        'aboutText' => 'This free GCSE financial literacy game covers basic UK consumer rights at a concept level, including what to expect when goods are faulty and how to make a complaint. Choose your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What right do you usually have if you buy goods that turn out to be faulty?", a: "The right to a repair, replacement or refund" },
                { q: "What does 'consumer rights' mean?", a: "Legal protections that help buyers when something they purchase goes wrong" },
                { q: "In the UK, what law mainly protects consumers when they buy goods and services?", a: "The Consumer Rights Act" },
                { q: "What should goods be, according to UK consumer law, when you buy them?", a: "Of satisfactory quality, fit for purpose, and as described" },
                { q: "What is a 'receipt' useful for as a consumer?", a: "Proof of purchase, needed to support a refund or complaint" },
                { q: "If an item breaks shortly after you buy it through no fault of your own, what can you usually ask for?", a: "A repair, replacement or refund" },
                { q: "What is a 'warranty' or 'guarantee'?", a: "A promise from a retailer or manufacturer to fix or replace an item within a certain time" },
                { q: "Do consumer rights only apply to things bought in shops?", a: "No — they also apply to things bought online or by phone" },
                { q: "Why is it important to know your consumer rights before buying something expensive?", a: "So you know what to do and what you're entitled to if something goes wrong" },
                { q: "What might you do first if you have a problem with something you've bought?", a: "Contact the seller/retailer and explain the problem" },
                { q: "What is 'misleading advertising'?", a: "Advertising that gives a false or unfair impression of a product" },
                { q: "Why do consumer rights exist?", a: "To protect buyers from being treated unfairly by sellers" },
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
                storageKey: 'consumerRightsGame.settings',
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
                    return "Think about what protections a buyer has when something they've bought goes wrong.";
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You know your consumer rights really well!",
            });
        })();
    </script>
@endpush
