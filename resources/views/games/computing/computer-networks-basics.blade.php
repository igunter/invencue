@extends('layouts.app')

@section('meta_title', 'Computer Networks Basics — Computing Game for Kids')
@section('meta_blurb', 'A free computing game — learn key network facts about LAN, WAN, wifi and routers.')
@section('meta_words', 'computer networks game, LAN WAN game, wifi router quiz, ks3 computing game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-wifi',
        'title' => 'Computer Networks Basics',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Networks basics'],
        ],
        'aboutTitle' => 'About this computer networks basics game',
        'aboutText' => 'This free computing game covers the basics of computer networks — LANs, WANs, the internet, Wi-Fi and routers — and the key words used to describe how devices connect together.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What do we call a network of computers connected together in one building, like a school?", a: "A LAN (Local Area Network)" },
                { q: "What do we call a network that connects computers over a large area, like across a country?", a: "A WAN (Wide Area Network)" },
                { q: "What is the huge network that connects millions of networks all over the world called?", a: "The internet" },
                { q: "What device sends internet signals around your home, often using cables and wireless?", a: "A router" },
                { q: "What do we call wireless internet connection using radio waves instead of cables?", a: "Wi-Fi" },
                { q: "What is the name for a device connected to a network, like a computer or printer?", a: "A node" },
                { q: "What is the world's biggest WAN, connecting networks globally?", a: "The internet" },
                { q: "What do we call the cables used to physically connect devices in many wired networks?", a: "Ethernet cables" },
                { q: "What is a network that only lets approved computers and people access it, often used by businesses, called?", a: "A private network" },
                { q: "What is a network that anyone can join, like at a cafe, often called?", a: "A public network (public Wi-Fi)" },
                { q: "What do we call the speed at which data travels across a network?", a: "Bandwidth" },
                { q: "What is the device called that connects a home network to the internet provider?", a: "A router (or modem)" },
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
                storageKey: 'computerNetworksBasicsGame.settings',
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
                    return 'Think about how big the network is, and what device or connection type is being described.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a computer networks superstar!",
            });
        })();
    </script>
@endpush
