@extends('layouts.app')

@section('meta_title', 'Waves & Radiation — GCSE Physics Game')
@section('meta_blurb', 'A free GCSE physics game covering the electromagnetic spectrum and wave speed calculations (v = fλ).')
@section('meta_words', 'waves game, electromagnetic spectrum game, gcse physics game, wave speed calculation, v=f lambda, radiation revision')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-signpost-split',
        'title' => 'Waves & Radiation',
        'subtitle' => 'Pick your question types, then test your wave knowledge!',
        'typeToggles' => [
            ['id' => 'spectrum', 'label' => 'EM spectrum'],
            ['id' => 'waveCalc', 'label' => 'Wave speed calculations'],
        ],
        'aboutTitle' => 'About this waves & radiation game',
        'aboutText' => 'This free GCSE physics game covers the electromagnetic spectrum — from radio waves to gamma rays — and the wave speed equation v = f × λ, with randomised numbers each time. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const SPECTRUM = {
                'Radio waves': 'The longest wavelength and lowest frequency in the EM spectrum; used for broadcasting and communications.',
                'Microwaves': 'Used for satellite communication and cooking food; shorter wavelength than radio waves.',
                'Infrared': 'Emitted by warm objects; used in remote controls, thermal imaging and cooking.',
                'Visible light': 'The only part of the EM spectrum humans can see, ranging from red to violet.',
                'Ultraviolet': 'Can damage skin cells and eyes, and causes fluorescent materials to glow.',
                'X-rays': 'Very short wavelength and high energy; used to image bones and in airport security.',
                'Gamma rays': 'The shortest wavelength and highest frequency and energy; produced by radioactive decay and used to treat cancer.',
            };
            const SPECTRUM_NAMES = Object.keys(SPECTRUM);

            // 1 and 2 are excluded from FREQUENCIES: they can make the
            // "divide instead of multiply" distractor collide with the
            // correct answer for small wavelengths.
            const FREQUENCIES = [10, 20, 50, 100, 200];
            const WAVELENGTHS = [3, 4, 5, 10];

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
                storageKey: 'wavesRadiationGame.settings',
                types: ['spectrum', 'waveCalc'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'spectrum') {
                        const wave = SPECTRUM_NAMES[randInt(0, SPECTRUM_NAMES.length - 1)];
                        return {
                            category: type,
                            label: wave,
                            correctText: SPECTRUM[wave],
                            questionText: 'What is true of ' + wave.toLowerCase() + '?',
                        };
                    }
                    const freq = FREQUENCIES[randInt(0, FREQUENCIES.length - 1)];
                    const wavelength = WAVELENGTHS[randInt(0, WAVELENGTHS.length - 1)];
                    return {
                        category: type,
                        freq: freq,
                        wavelength: wavelength,
                        correctText: (freq * wavelength) + ' m/s',
                        questionText: 'A wave has a frequency of ' + freq + ' Hz and a wavelength of ' + wavelength + ' m. What is its speed? (v = f × λ)',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'spectrum') {
                        const distractors = pickOthers(SPECTRUM_NAMES, q.label, 3).map(function(w) { return SPECTRUM[w]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const correct = q.freq * q.wavelength;
                    const wrongAdd = (q.freq + q.wavelength) + ' m/s';
                    const wrongDivide = Math.round((q.freq / q.wavelength) * 100) / 100 + ' m/s';
                    const wrongDouble = (correct * 2) + ' m/s';
                    return shuffle([correct + ' m/s', wrongAdd, wrongDivide, wrongDouble]);
                },

                hintFor: function(q) {
                    if (q.category === 'spectrum') {
                        return 'Radio waves have the longest wavelength and least energy; gamma rays have the shortest wavelength and most energy. Everything else sits in between, in that order.';
                    }
                    return 'v = f × λ. Multiply the frequency (in Hz) by the wavelength (in m).';
                },

                explanationFor: function(q) {
                    if (q.category === 'waveCalc') return q.freq + ' Hz × ' + q.wavelength + ' m = ' + q.correctText;
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered waves and radiation!",
            });
        })();
    </script>
@endpush
