@extends('layouts.app')

@section('meta_title', 'Internet Basics — Computing Game for Kids')
@section('meta_blurb', 'A free computing game for kids — simple facts about websites, browsers and search engines.')
@section('meta_words', 'internet basics game, kids computing game, websites browsers quiz, search engines for kids, ks1 ks2 computing')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-globe',
        'title' => 'Internet Basics',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Internet basics'],
        ],
        'aboutTitle' => 'About this internet basics game',
        'aboutText' => 'This free computing game teaches young kids simple facts about the internet — what a website, browser and search engine are, and other everyday internet words.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What is a program that lets you look at websites called?", a: "A web browser" },
                { q: "What do we call the address you type in to visit a website, like www.example.com?", a: "A URL (web address)" },
                { q: "What is a website used to help you find information by typing in words called?", a: "A search engine" },
                { q: "What do we call a link on a webpage that takes you to another page when clicked?", a: "A hyperlink" },
                { q: "What is the huge network that connects computers all over the world called?", a: "The internet" },
                { q: "What is the first page you see when you open a website called?", a: "The homepage" },
                { q: "What do we call it when a webpage is loading slowly or won't open?", a: "Buffering (a slow connection)" },
                { q: "What symbol usually appears in an email address, like name@example.com?", a: "The @ symbol" },
                { q: "What do we call the small icon that appears in a browser tab next to a website's name?", a: "A favicon" },
                { q: "What is it called when you save a website so you can find it again easily?", a: "Bookmarking (adding a favourite)" },
                { q: "What do we call software that blocks pop-ups and unwanted adverts on some browsers?", a: "An ad blocker" },
                { q: "What is the collection of connected webpages about one topic or organisation called?", a: "A website" },
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
                storageKey: 'internetBasicsGame.settings',
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
                    return 'Think about the tools and words we use every time we go online.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're an internet basics superstar!",
            });
        })();
    </script>
@endpush
