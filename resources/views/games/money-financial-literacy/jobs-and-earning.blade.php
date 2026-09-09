@extends('layouts.app')

@section('meta_title', 'Jobs & Earning — Money Game for Kids')
@section('meta_blurb', 'A free money game covering jobs and earning — types of income, wages vs salary, and part-time jobs.')
@section('meta_words', 'jobs and earning game, wages vs salary, part time jobs for kids, kids money game, ks3 money game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-person-workspace',
        'title' => 'Jobs & Earning',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Jobs & earning facts'],
        ],
        'aboutTitle' => 'About this jobs & earning game',
        'aboutText' => 'This free money game covers different types of income and jobs — wages, salaries, part-time work and self-employment. Choose your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What is a 'wage'?", a: "Pay usually calculated by the hour or day worked" },
                { q: "What is a 'salary'?", a: "A fixed yearly amount of pay, usually paid monthly" },
                { q: "What is a part-time job?", a: "A job where you work fewer hours than a full-time job" },
                { q: "What does 'self-employed' mean?", a: "Working for yourself rather than for an employer" },
                { q: "What might a teenager do to earn some extra money?", a: "A part-time job, like a paper round or babysitting" },
                { q: "What is an 'employer'?", a: "A person or company that pays someone to work for them" },
                { q: "What is an 'employee'?", a: "A person who is paid to work for an employer" },
                { q: "Why might people with more training or experience often earn more?", a: "Their skills and experience are usually more in demand" },
                { q: "What is 'freelance' work?", a: "Working for different clients on your own terms, rather than one employer" },
                { q: "Why is it useful to compare pay when choosing between two jobs?", a: "So you can choose the one that pays fairly for the work involved" },
                { q: "What might someone do with the money they earn from a part-time job?", a: "Save some, spend some, and maybe give some away" },
                { q: "Besides money, what else might someone gain from having a job?", a: "Experience, skills and confidence" },
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
                storageKey: 'jobsAndEarningGame.settings',
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
                    return 'Think about the different ways people can earn money through work.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You understand jobs and earning really well!",
            });
        })();
    </script>
@endpush
