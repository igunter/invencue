@extends('layouts.app')

@section('meta_title', 'Music Theory Basics — Music Game for Kids')
@section('meta_blurb', 'A free music game — learn simple scale and key signature concepts, from tones and semitones to sharps and flats.')
@section('meta_words', 'music theory basics game, scales key signatures, sharps flats, tones semitones, ks3 music game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-journal-bookmark',
        'title' => 'Music Theory Basics',
        'subtitle' => 'Read the clue, then work out the term!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Theory terms'],
        ],
        'aboutTitle' => 'About this music theory basics game',
        'aboutText' => 'This free music game introduces simple scale and key signature concepts, including tones, semitones, sharps, flats and the difference between major and minor scales.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Scale': 'A set of notes played in order, going up or down in pitch.',
                'Major scale': 'A scale with a bright, happy sound.',
                'Minor scale': 'A scale with a darker, sadder sound than a major scale.',
                'Key signature': 'The sharps or flats shown at the start of a piece, telling you which key it is in.',
                'Tonic': "The first and 'home' note of a scale or key.",
                'Octave': 'The distance between one note and the next note of the same name, higher or lower.',
                'Semitone': 'The smallest step between two notes in Western music.',
                'Tone (whole step)': 'A step equal to two semitones.',
                'Sharp (♯)': 'A symbol that raises a note by one semitone.',
                'Flat (♭)': 'A symbol that lowers a note by one semitone.',
                'Natural (♮)': 'A symbol that cancels a previous sharp or flat.',
                'Interval': 'The distance in pitch between two notes.',
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
                storageKey: 'musicTheoryBasicsGame.settings',
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
                    return 'Think about whether this term is about pitch, distance between notes, or a symbol.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You're a music theory superstar!",
            });
        })();
    </script>
@endpush
