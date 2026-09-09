@extends('layouts.app')

@section('meta_title', 'Waves — Kids Physics Game')
@section('meta_blurb', 'A free physics game for kids — label the parts of a wave (amplitude, wavelength, frequency) and tell transverse waves apart from longitudinal waves.')
@section('meta_words', 'waves game, kids physics game, amplitude wavelength frequency, transverse longitudinal waves')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-symmetry-vertical',
        'title' => 'Waves',
        'subtitle' => 'Pick your question types, then test your wave knowledge!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'parts', 'label' => 'Parts of a wave'],
            ['id' => 'types', 'label' => 'Wave types'],
        ],
        'aboutTitle' => 'About this waves game',
        'aboutText' => 'This free physics game covers the parts of a wave — amplitude, wavelength, frequency, crest and trough — plus the difference between transverse waves and longitudinal waves. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const PARTS = {
                'Amplitude': 'The maximum height of a wave from its resting position — related to how much energy it carries.',
                'Wavelength': 'The distance from one point on a wave to the same point on the next wave, like crest to crest.',
                'Frequency': 'The number of waves passing a point every second, measured in hertz (Hz).',
                'Crest': 'The highest point of a wave.',
                'Trough': 'The lowest point of a wave.',
            };
            const PART_NAMES = Object.keys(PARTS);

            const TYPES = {
                'Transverse wave': 'The particles vibrate at right angles to the direction the wave travels, like light or water waves.',
                'Longitudinal wave': 'The particles vibrate in the same direction the wave travels, like sound waves.',
            };

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
                storageKey: 'wavesGame.settings',
                types: ['parts', 'types'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'parts') {
                        const part = PART_NAMES[randInt(0, PART_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: PARTS[part],
                            questionText: "What is the '" + part + "' of a wave?",
                        };
                    }
                    const waveType = randInt(0, 1) === 0 ? 'Transverse wave' : 'Longitudinal wave';
                    return {
                        category: type,
                        label: waveType,
                        correctText: TYPES[waveType],
                        questionText: "What happens in a '" + waveType + "'?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'parts') {
                        const part = PART_NAMES.find(function(p) { return PARTS[p] === q.correctText; });
                        const distractors = pickOthers(PART_NAMES, part, 3).map(function(p) { return PARTS[p]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    return shuffle([TYPES['Transverse wave'], TYPES['Longitudinal wave']]);
                },

                hintFor: function(q) {
                    if (q.category === 'parts') {
                        return 'Think about height, distance between repeats, waves per second, the top, or the bottom.';
                    }
                    return 'Think about whether the vibration is sideways (at right angles) or back-and-forth along the same line the wave travels.';
                },

                explanationFor: function(q) {
                    if (q.category === 'types') return q.label + ': ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a waves superstar!",
            });
        })();
    </script>
@endpush
