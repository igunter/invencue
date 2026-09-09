@extends('layouts.app')

@section('meta_title', 'Types of Technology — Computing Game for Kids')
@section('meta_blurb', 'A free computing game for kids — spot everyday devices that use computers, like tablets, phones and smart TVs.')
@section('meta_words', 'types of technology game, kids computing game, everyday devices quiz, smart devices for kids, ks1 ks2 computing')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-phone',
        'title' => 'Types of Technology',
        'subtitle' => 'Read the clue, then work out the device!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Technology'],
        ],
        'aboutTitle' => 'About this types of technology game',
        'aboutText' => 'This free computing game helps young kids spot the everyday devices that use computers all around them, from smartphones and tablets to smart TVs and voice assistants.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "Which small device do you carry in your pocket to make calls and use apps?", a: "A smartphone" },
                { q: "Which flat touchscreen device is bigger than a phone but doesn't have a keyboard attached?", a: "A tablet" },
                { q: "Which device lets you watch shows and connect to apps like a computer, but through your TV?", a: "A smart TV" },
                { q: "What do we call a smartwatch that can count your steps and show messages?", a: "A wearable device" },
                { q: "Which device in the kitchen can be programmed to heat food using buttons and a screen?", a: "A microwave (smart appliance)" },
                { q: "What is a device called that you talk to and it answers using a computer voice, like Alexa?", a: "A smart speaker (voice assistant)" },
                { q: "Which machine at a shop lets you pay for your own items without a cashier?", a: "A self-checkout machine" },
                { q: "What do we call a robot vacuum cleaner that moves around a room by itself?", a: "A smart/robot device" },
                { q: "Which device do doctors use that has a computer inside to take pictures of the body?", a: "A scanner (medical imaging machine)" },
                { q: "What is a fitness tracker that goes on your wrist an example of?", a: "Wearable technology" },
                { q: "Which device in a car uses a computer to help the driver find directions?", a: "A satnav (GPS device)" },
                { q: "What do we call everyday objects like fridges and lights that connect to the internet?", a: "Smart devices (Internet of Things)" },
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
                storageKey: 'typesOfTechnologyGame.settings',
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
                    return 'Think about where you would see or use this kind of device in everyday life.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a technology detective superstar!",
            });
        })();
    </script>
@endpush
