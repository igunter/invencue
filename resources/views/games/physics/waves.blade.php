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
                'Period': 'The time taken for one complete wave to pass a point, measured in seconds.',
                'Wave speed': 'How fast a wave travels, measured in metres per second (m/s).',
                'Node': 'A point on a standing wave where there is no vibration.',
                'Antinode': 'A point on a standing wave with the maximum vibration.',
                'Oscillation': 'One complete vibration, from the resting position up to the crest, back down through the resting position to the trough, and back to resting.',
            };
            const PART_NAMES = Object.keys(PARTS);

            const TYPES = {
                'Transverse wave': 'The particles vibrate at right angles to the direction the wave travels, like light or water waves.',
                'Longitudinal wave': 'The particles vibrate in the same direction the wave travels, like sound waves.',
                'Mechanical wave': 'A wave that needs a medium (like water, air or a solid) to travel through — it cannot travel through a vacuum.',
                'Electromagnetic wave': 'A wave that can travel through a vacuum, like light, radio waves and X-rays.',
                'Water wave': 'A transverse wave on the surface of water — the water moves up and down as the wave travels sideways.',
                'Sound wave': 'A longitudinal wave made of compressions and rarefactions travelling through a medium.',
                'Compression': 'A squashed-together region of particles in a longitudinal wave, where pressure is highest.',
                'Rarefaction': 'A spread-out region of particles in a longitudinal wave, where pressure is lowest.',
                'Standing (stationary) wave': 'A wave formed when two waves of the same frequency travel in opposite directions and overlap, appearing to stay still.',
                'Ripple spreading across a pond': 'An example of a transverse wave, where the water surface moves up and down as the ripple spreads outwards.',
            };
            const TYPE_NAMES = Object.keys(TYPES);

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
                    const waveType = TYPE_NAMES[randInt(0, TYPE_NAMES.length - 1)];
                    return {
                        category: type,
                        label: waveType,
                        correctText: TYPES[waveType],
                        questionText: waveType + ' — what is true of this?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'parts') {
                        const part = PART_NAMES.find(function(p) { return PARTS[p] === q.correctText; });
                        const distractors = pickOthers(PART_NAMES, part, 3).map(function(p) { return PARTS[p]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(TYPE_NAMES, q.label, 3).map(function(t) { return TYPES[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'parts') {
                        return 'Think about height, distance between repeats, waves per second, time per wave, the top, the bottom, or standing wave points.';
                    }
                    return 'Think about the direction of vibration, whether it needs a medium, and whether it involves compressions, rarefactions or a moving surface.';
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
