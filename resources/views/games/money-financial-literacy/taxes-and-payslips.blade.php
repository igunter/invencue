@extends('layouts.app')

@section('meta_title', 'Taxes & Payslips — GCSE Financial Literacy Game')
@section('meta_blurb', 'A free GCSE financial literacy game covering taxes and payslips — what income tax and National Insurance are.')
@section('meta_words', 'taxes and payslips game, income tax explained, national insurance, payslip explained, gcse financial literacy game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-file-earmark-text',
        'title' => 'Taxes & Payslips',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Tax & payslip facts'],
        ],
        'aboutTitle' => 'About this taxes & payslips game',
        'aboutText' => 'This free GCSE financial literacy game covers what income tax and National Insurance are, and how to read the basics of a UK payslip. Choose your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What is income tax?", a: "A tax taken from money people earn, paid to the government" },
                { q: "What is National Insurance?", a: "A UK tax that helps pay for state benefits like the NHS and pensions" },
                { q: "What is 'gross pay'?", a: "The total amount someone earns before any tax or deductions are taken off" },
                { q: "What is 'net pay' (take-home pay)?", a: "The amount left after tax and other deductions have been taken from gross pay" },
                { q: "What is a payslip?", a: "A document showing how much someone was paid and what was deducted" },
                { q: "Why does the government collect income tax?", a: "To pay for public services like schools, hospitals and roads" },
                { q: "What is a 'deduction' on a payslip?", a: "An amount taken off your pay before you receive it, such as tax" },
                { q: "Who usually takes income tax and National Insurance out of an employee's pay?", a: "Their employer, before paying them their wages" },
                { q: "What might a payslip show alongside tax and National Insurance?", a: "Pension contributions and gross/net pay" },
                { q: "Why is it useful to check your payslip carefully?", a: "To make sure you've been paid correctly and deductions are right" },
                { q: "What is a workplace pension contribution?", a: "Money taken from pay and saved for retirement, often alongside employer contributions" },
                { q: "Why do most employees pay both income tax and National Insurance?", a: "They fund different things — general public services, and specific benefits like the NHS and state pension" },
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
                storageKey: 'taxesAndPayslipsGame.settings',
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
                    return 'Think about what is taken from your pay, and why, before you receive your take-home pay.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You've mastered taxes and payslips!",
            });
        })();
    </script>
@endpush
