@extends('layouts.app')

@section('meta_title', 'Input & Output Devices — Computing Game for Kids')
@section('meta_blurb', 'A free computing game — classify devices as input, output or both, and learn what each device does.')
@section('meta_words', 'input output devices game, ks3 computing game, computer devices quiz')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-mouse',
        'title' => 'Input & Output Devices',
        'subtitle' => 'Pick your question types, then classify the devices!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'classify', 'label' => 'Input, output or both?'],
            ['id' => 'facts', 'label' => 'Device facts'],
        ],
        'aboutTitle' => 'About this input & output devices game',
        'aboutText' => 'This free computing game helps you classify everyday devices as input, output or both, and learn what job each device does when sending data into or out of a computer.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const DEVICES = {
                'Keyboard': 'Input',
                'Mouse': 'Input',
                'Microphone': 'Input',
                'Scanner': 'Input',
                'Monitor': 'Output',
                'Printer': 'Output',
                'Speakers': 'Output',
                'Projector': 'Output',
                'Touchscreen': 'Both',
                'Headset with a microphone': 'Both',
                'Webcam': 'Input',
                'Graphics tablet (drawing pad)': 'Input',
            };
            const DEVICE_NAMES = Object.keys(DEVICES);

            const FACTS = {
                'What is any device that sends data into a computer called?': 'An input device',
                'What is any device that a computer uses to send information out to the user called?': 'An output device',
                'Which input device lets you type text and commands?': 'A keyboard',
                'Which output device shows pictures and text on a screen?': 'A monitor',
                'Which input device converts sound into data the computer can use?': 'A microphone',
                'Which output device produces sound from a computer?': 'Speakers',
                "Which device puts a paper document's picture into the computer as data?": 'A scanner',
                'Which output device puts a digital document onto paper?': 'A printer',
                'Why is a touchscreen considered both an input and an output device?': 'It displays images (output) and lets you touch it to give commands (input)',
                'Which input device lets you move a pointer and click on things on screen?': 'A mouse',
            };
            const FACT_QUESTIONS = Object.keys(FACTS);

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
                storageKey: 'inputOutputDevicesGame.settings',
                types: ['classify', 'facts'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'classify') {
                        const device = DEVICE_NAMES[randInt(0, DEVICE_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: DEVICES[device],
                            questionText: device + ' — is this an input device, an output device, or both?',
                        };
                    }
                    const question = FACT_QUESTIONS[randInt(0, FACT_QUESTIONS.length - 1)];
                    return {
                        category: type,
                        label: question,
                        correctText: FACTS[question],
                        questionText: question,
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'classify') {
                        return shuffle(['Input', 'Output', 'Both']);
                    }
                    const distractors = pickOthers(FACT_QUESTIONS, q.label, 3).map(function(f) { return FACTS[f]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'classify') {
                        return 'Does it send data into the computer, show data out, or can it do both?';
                    }
                    return 'Think about whether the device sends data in or shows data out.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're an input & output devices superstar!",
            });
        })();
    </script>
@endpush
