@extends('layouts.app')

@section('meta_title', 'Sounds Around Us — Kids Physics Game')
@section('meta_blurb', 'A free physics game for young kids — sort sounds by loud or quiet, and by high or low pitch.')
@section('meta_words', 'sounds game, kids physics game, loud quiet, high low pitch, learn about sound for kids')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-chat-left-text',
        'title' => 'Sounds Around Us',
        'subtitle' => 'Pick your question types, then sort those sounds!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'volume', 'label' => 'Loud or quiet?'],
            ['id' => 'pitch', 'label' => 'High or low?'],
        ],
        'aboutTitle' => 'About this sounds around us game',
        'aboutText' => 'This free physics game helps young kids sort everyday sounds by how loud or quiet they are, and by whether their pitch is high or low. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const VOLUME = {
                'A jet engine': 'Loud',
                'A rocket launching': 'Loud',
                'Thunder': 'Loud',
                'A whisper': 'Quiet',
                'A ticking clock': 'Quiet',
                'Leaves rustling in the wind': 'Quiet',
            };
            const VOLUME_NAMES = Object.keys(VOLUME);

            const PITCH = {
                'A mouse squeaking': 'High',
                'A whistle': 'High',
                'A small bird singing': 'High',
                'A lion roaring': 'Low',
                'A bass drum': 'Low',
                'Thunder rumbling': 'Low',
            };
            const PITCH_NAMES = Object.keys(PITCH);

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
                storageKey: 'soundsAroundUsGame.settings',
                types: ['volume', 'pitch'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'volume') {
                        const sound = VOLUME_NAMES[randInt(0, VOLUME_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: VOLUME[sound],
                            questionText: sound + ' — is this loud or quiet?',
                        };
                    }
                    const sound = PITCH_NAMES[randInt(0, PITCH_NAMES.length - 1)];
                    return {
                        category: type,
                        correctText: PITCH[sound],
                        questionText: sound + ' — is this a high or low sound?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'volume') {
                        return shuffle(['Loud', 'Quiet']);
                    }
                    return shuffle(['High', 'Low']);
                },

                hintFor: function(q) {
                    if (q.category === 'volume') {
                        return 'Would this hurt your ears if it was close to you, or would you have to stay very quiet to hear it?';
                    }
                    return 'Try to imagine the sound — is it squeaky and light, or deep and rumbly?';
                },

                explanationFor: function(q) {
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a sounds around us superstar!",
            });
        })();
    </script>
@endpush
