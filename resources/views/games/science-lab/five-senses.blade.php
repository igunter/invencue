@extends('layouts.app')

@section('meta_title', 'Five Senses — Kids Science Game')
@section('meta_blurb', 'A free science game for young kids — match an observation to the sense used, and to the body part that makes it possible.')
@section('meta_words', 'five senses game, kids science game, sight hearing smell taste touch, eyes ears nose tongue skin')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-eye',
        'title' => 'Five Senses',
        'subtitle' => 'Pick your question types, then test your senses knowledge!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'observations', 'label' => 'Which sense?'],
            ['id' => 'organs', 'label' => 'Which body part?'],
        ],
        'aboutTitle' => 'About this five senses game',
        'aboutText' => 'This free science game helps young kids match an observation to the sense used to make it, and each sense to the body part that makes it possible — eyes, ears, nose, tongue and skin. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const OBSERVATIONS = {
                'Noticing a cake smells sweet': 'Smell',
                'Hearing a dog bark': 'Hearing',
                'Seeing bright colours in a rainbow': 'Sight',
                'Feeling that ice is cold': 'Touch',
                'Tasting that a lemon is sour': 'Taste',
                'Hearing thunder rumble in the distance': 'Hearing',
                'Feeling that sandpaper is rough': 'Touch',
                'Seeing that the sky is cloudy': 'Sight',
                'Smelling smoke from a bonfire': 'Smell',
                'Tasting that a crisp is salty': 'Taste',
            };
            const OBSERVATION_NAMES = Object.keys(OBSERVATIONS);
            const SENSE_LIST = ['Smell', 'Hearing', 'Sight', 'Touch', 'Taste'];

            const ORGANS = {
                'Sight': 'Eyes',
                'Hearing': 'Ears',
                'Smell': 'Nose',
                'Taste': 'Tongue',
                'Touch': 'Skin',
                'Balance': 'Inner ear',
                'Body position (proprioception)': 'Muscles and joints',
                'Temperature (thermoception)': 'Nerve endings in the skin',
                'Hunger': 'Stomach',
                'Thirst': 'Brain (hypothalamus)',
            };
            const ORGAN_SENSE_LIST = ['Sight', 'Hearing', 'Smell', 'Taste', 'Touch', 'Balance', 'Body position (proprioception)', 'Temperature (thermoception)', 'Hunger', 'Thirst'];

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
                storageKey: 'fiveSensesGame.settings',
                types: ['observations', 'organs'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'observations') {
                        const observation = OBSERVATION_NAMES[randInt(0, OBSERVATION_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: OBSERVATIONS[observation],
                            questionText: observation + ' — which sense is this?',
                        };
                    }
                    const sense = ORGAN_SENSE_LIST[randInt(0, ORGAN_SENSE_LIST.length - 1)];
                    return {
                        category: type,
                        label: sense,
                        correctText: ORGANS[sense],
                        questionText: 'Which body part do you use for ' + sense.toLowerCase() + '?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'observations') {
                        const distractors = pickOthers(SENSE_LIST, q.correctText, 3);
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(ORGAN_SENSE_LIST.map(function(s) { return ORGANS[s]; }), q.correctText, 3);
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'observations') {
                        return 'Think about whether you noticed this with your eyes, ears, nose, tongue, or skin.';
                    }
                    return 'Think about seeing, hearing, smelling, tasting, or touching.';
                },

                explanationFor: function(q) {
                    if (q.category === 'organs') return q.label + ': ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a five senses superstar!",
            });
        })();
    </script>
@endpush
