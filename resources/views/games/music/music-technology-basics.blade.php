@extends('layouts.app')

@section('meta_title', 'Music Technology Basics — GCSE Music Game')
@section('meta_blurb', 'A free GCSE music game covering simple music technology facts — DAWs, sampling, MIDI and recording basics.')
@section('meta_words', 'gcse music technology game, daw sampling midi, recording basics quiz, gcse music revision')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-cpu',
        'title' => 'Music Technology Basics',
        'subtitle' => 'Read the clue, then work out the term!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Technology terms'],
        ],
        'aboutTitle' => 'About this music technology basics game',
        'aboutText' => 'This free GCSE music game covers simple music technology facts, including DAWs, sampling, MIDI, mixing and recording basics used in modern music production.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'DAW (Digital Audio Workstation)': 'Software used to record, edit and produce music, like Logic Pro or Ableton Live.',
                'MIDI': 'A system that lets electronic instruments and computers communicate musical performance data like pitch and timing.',
                'Sampling': 'Taking a section of a recorded sound and reusing it in a new piece of music.',
                'Sequencing': 'Arranging and editing recorded or programmed musical notes and audio in a timeline.',
                'Microphone': 'A device that converts sound waves into an electrical signal for recording or amplification.',
                'Mixing': 'Balancing the volume, tone and placement of different recorded tracks together.',
                'Mastering': 'The final stage of production, polishing a mix so it sounds consistent and ready for release.',
                'Reverb': 'An audio effect that simulates the natural echo of a sound in a room or space.',
                'Compression (audio)': 'An effect that reduces the difference between the loudest and quietest parts of a sound.',
                'Loop': 'A short section of audio or MIDI that repeats continuously.',
                'Sample rate': 'How many times per second an audio signal is measured when it is recorded digitally.',
                'Synthesiser': 'An electronic instrument that generates sounds electronically rather than acoustically.',
            };
            const TERM_NAMES = Object.keys(TERMS);

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
                storageKey: 'musicTechnologyBasicsGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const term = TERM_NAMES[randInt(0, TERM_NAMES.length - 1)];
                    return {
                        category: type,
                        label: term,
                        correctText: TERMS[term],
                        questionText: "What does '" + term + "' mean?",
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about whether this is a piece of software, hardware, an effect, or a recording process.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You're a music technology superstar!",
            });
        })();
    </script>
@endpush
