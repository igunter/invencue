@extends('layouts.app')

@section('meta_title', 'Networks & Topologies — GCSE Computer Science Game')
@section('meta_blurb', 'A free GCSE computer science game covering star and bus topologies, and key protocols like HTTP and TCP/IP.')
@section('meta_words', 'networks topologies game, gcse computer science game, star bus topology quiz, HTTP TCP/IP protocols')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-diagram-2',
        'title' => 'Networks & Topologies',
        'subtitle' => 'Pick your question types, then test your networks knowledge!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'topologies', 'label' => 'Topologies'],
            ['id' => 'protocols', 'label' => 'Protocols'],
        ],
        'aboutTitle' => 'About this networks & topologies game',
        'aboutText' => 'This free GCSE computer science game covers network topologies — star and bus — and key protocols like HTTP, HTTPS and TCP/IP that let devices communicate across networks.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TOPOLOGIES = {
                'In a star topology, how are devices connected?': 'Each device connects individually to a central switch or hub',
                'What is a key advantage of a star topology?': 'If one cable fails, only that device loses connection — the rest keep working',
                'In a bus topology, how are devices connected?': 'All devices connect to a single central cable (the backbone)',
                'What is a disadvantage of a bus topology?': 'If the main cable fails, the whole network goes down',
                'What device sits at the centre of a star topology, directing data to the correct device?': 'A switch (or hub)',
                'Which topology is generally more reliable but needs more cabling: star or bus?': 'Star topology',
                'What do we call the network topology where devices are connected in a closed loop, each to its neighbours?': 'Ring topology',
                'What term describes a network confined to one building or site?': 'LAN (Local Area Network)',
                'What term describes a network that spans multiple sites, often connected via the internet?': 'WAN (Wide Area Network)',
                'Why do many offices choose a star topology despite the extra cabling?': "It's more reliable and easier to add new devices",
            };
            const TOPOLOGY_QUESTIONS = Object.keys(TOPOLOGIES);

            const PROTOCOLS = {
                'What protocol is used to transfer webpages between a web server and a browser?': 'HTTP (HyperText Transfer Protocol)',
                'What is the secure, encrypted version of HTTP called?': 'HTTPS',
                'What set of protocols manages how data is broken into packets and delivered across a network?': 'TCP/IP',
                'What does TCP stand for, the protocol that ensures data packets arrive correctly and in order?': 'Transmission Control Protocol',
                'What does IP stand for, the protocol that gives each device a unique address on a network?': 'Internet Protocol',
                'What is the unique numerical address given to a device on a network called?': 'An IP address',
                'What protocol is used to send email between mail servers?': 'SMTP (Simple Mail Transfer Protocol)',
                'What protocol is used to transfer files between computers over a network?': 'FTP (File Transfer Protocol)',
                'Why is data split into smaller pieces before being sent across a network?': "It's broken into packets so it can travel efficiently and be reassembled correctly",
                'What do we call the agreed rules that allow devices to communicate on a network?': 'A protocol',
            };
            const PROTOCOL_QUESTIONS = Object.keys(PROTOCOLS);

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

            function pickOthers(pool, exclude, count) {
                return shuffle(pool.filter(function(x) { return x !== exclude; })).slice(0, count);
            }

            window.ScienceQuiz.run({
                storageKey: 'networksTopologiesGame.settings',
                types: ['topologies', 'protocols'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'topologies') {
                        const question = TOPOLOGY_QUESTIONS[randInt(0, TOPOLOGY_QUESTIONS.length - 1)];
                        return {
                            category: type,
                            label: question,
                            correctText: TOPOLOGIES[question],
                            questionText: question,
                        };
                    }
                    const question = PROTOCOL_QUESTIONS[randInt(0, PROTOCOL_QUESTIONS.length - 1)];
                    return {
                        category: type,
                        label: question,
                        correctText: PROTOCOLS[question],
                        questionText: question,
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'topologies') {
                        const distractors = pickOthers(TOPOLOGY_QUESTIONS, q.label, 3).map(function(t) { return TOPOLOGIES[t]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(PROTOCOL_QUESTIONS, q.label, 3).map(function(p) { return PROTOCOLS[p]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'topologies') {
                        return 'Picture the layout of cables and devices, and think about what happens if one cable fails.';
                    }
                    return 'Think about what job this protocol does — webpages, email, files, or addressing devices.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You've mastered networks & topologies!",
            });
        })();
    </script>
@endpush
