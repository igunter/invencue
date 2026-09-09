@extends('layouts.app')

@section('meta_title', 'Music Theory — GCSE Music Game')
@section('meta_blurb', 'A free GCSE music game covering intervals and chords, including triads, inversions and cadences.')
@section('meta_words', 'gcse music theory game, intervals chords quiz, triads inversions cadences, gcse music revision')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-key',
        'title' => 'Music Theory',
        'subtitle' => 'Pick your question types, then test your theory knowledge!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'intervals', 'label' => 'Intervals'],
            ['id' => 'chords', 'label' => 'Chords'],
        ],
        'aboutTitle' => 'About this music theory game',
        'aboutText' => 'This free GCSE music game covers intervals and chords in more depth, including major and minor triads, inversions, seventh chords and cadences — key theory knowledge for GCSE Music. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const INTERVALS = {
                'Minor 2nd': 'A one-semitone gap between two notes, e.g. C to D♭.',
                'Major 2nd': 'A two-semitone gap between two notes, e.g. C to D.',
                'Minor 3rd': 'A three-semitone gap between two notes, e.g. C to E♭.',
                'Major 3rd': 'A four-semitone gap between two notes, e.g. C to E.',
                'Perfect 4th': 'A five-semitone gap between two notes, e.g. C to F.',
                'Tritone': 'A six-semitone gap, exactly half an octave, e.g. C to F♯.',
                'Perfect 5th': 'A seven-semitone gap between two notes, e.g. C to G.',
                'Minor 6th': 'An eight-semitone gap between two notes, e.g. C to A♭.',
                'Major 6th': 'A nine-semitone gap between two notes, e.g. C to A.',
                'Minor 7th': 'A ten-semitone gap between two notes, e.g. C to B♭.',
                'Major 7th': 'An eleven-semitone gap between two notes, e.g. C to B.',
                'Octave': 'A twelve-semitone gap between two notes of the same name.',
            };
            const INTERVAL_NAMES = Object.keys(INTERVALS);

            const CHORDS = {
                'Major triad': 'A three-note chord built from a root, major 3rd and perfect 5th, with a bright sound.',
                'Minor triad': 'A three-note chord built from a root, minor 3rd and perfect 5th, with a darker sound.',
                'Diminished triad': 'A three-note chord built from a root, minor 3rd and diminished 5th, with a tense, unstable sound.',
                'Augmented triad': 'A three-note chord built from a root, major 3rd and augmented 5th, with an unsettled sound.',
                'Root position': 'A chord with its root note as the lowest note.',
                'First inversion': 'A chord with its third as the lowest note.',
                'Second inversion': 'A chord with its fifth as the lowest note.',
                'Seventh chord': 'A four-note chord made by adding a 7th above the root of a triad.',
                'Tonic chord (I)': "The chord built on the first degree of the scale, the 'home' chord.",
                'Dominant chord (V)': 'The chord built on the fifth degree of the scale, which creates tension leading back to the tonic.',
                'Subdominant chord (IV)': 'The chord built on the fourth degree of the scale.',
                'Perfect cadence': 'A chord progression from dominant (V) to tonic (I) that sounds like a strong, final ending.',
            };
            const CHORD_NAMES = Object.keys(CHORDS);

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
                storageKey: 'musicTheoryGame.settings',
                types: ['intervals', 'chords'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'intervals') {
                        const term = INTERVAL_NAMES[randInt(0, INTERVAL_NAMES.length - 1)];
                        return {
                            category: type,
                            label: term,
                            correctText: INTERVALS[term],
                            questionText: "What is a '" + term + "'?",
                        };
                    }
                    const term = CHORD_NAMES[randInt(0, CHORD_NAMES.length - 1)];
                    return {
                        category: type,
                        label: term,
                        correctText: CHORDS[term],
                        questionText: "What is a '" + term + "'?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'intervals') {
                        const distractors = pickOthers(INTERVAL_NAMES, q.label, 3).map(function(t) { return INTERVALS[t]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(CHORD_NAMES, q.label, 3).map(function(t) { return CHORDS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'intervals') {
                        return 'Count the number of semitones between the two notes.';
                    }
                    return 'Think about how the chord is built and which note sits at the bottom.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered GCSE music theory!",
            });
        })();
    </script>
@endpush
