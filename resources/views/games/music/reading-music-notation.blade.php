@extends('layouts.app')

@section('meta_title', 'Reading Music Notation — Music Game for Kids')
@section('meta_blurb', 'A free music game — learn treble clef note names and simple note values like the crotchet, minim and semibreve.')
@section('meta_words', 'reading music notation game, treble clef notes, note values, crotchet minim semibreve, ks3 music game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-music-note-list',
        'title' => 'Reading Music Notation',
        'subtitle' => 'Pick your question types, then test your notation knowledge!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'notes', 'label' => 'Treble clef notes'],
            ['id' => 'values', 'label' => 'Note values'],
        ],
        'aboutTitle' => 'About this reading music notation game',
        'aboutText' => 'This free music game covers treble clef note names and simple note values such as the crotchet, minim and semibreve, helping you build the basics of reading written music. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const NOTES = [
                { q: "What are the names of the notes on the lines of the treble clef stave, from bottom to top?", a: "E, G, B, D, F" },
                { q: "What are the names of the notes in the spaces of the treble clef stave, from bottom to top?", a: "F, A, C, E" },
                { q: "What well-known phrase helps you remember the treble clef line notes (E, G, B, D, F)?", a: "Every Good Boy Deserves Food" },
                { q: "What word do the treble clef space notes (F, A, C, E) spell?", a: "FACE" },
                { q: "Which clef symbol curls around the second line from the bottom, marking that line as G?", a: "The treble clef (G clef)" },
                { q: "On a treble clef stave, which note sits on the middle line?", a: "B" },
                { q: "Which note sits on the second line from the bottom of the treble clef stave?", a: "G" },
                { q: "Which note sits in the second space from the bottom of the treble clef stave?", a: "A" },
                { q: "Which note sits in the bottom space of the treble clef stave?", a: "F" },
                { q: "Which note sits on the top line of the treble clef stave?", a: "F" },
            ];

            const VALUES = {
                'Semibreve': 'A note worth 4 beats (a whole note).',
                'Minim': 'A note worth 2 beats (a half note).',
                'Crotchet': 'A note worth 1 beat (a quarter note).',
                'Quaver': 'A note worth half a beat (an eighth note).',
                'Semiquaver': 'A note worth a quarter of a beat (a sixteenth note).',
                'Dotted crotchet': 'A note worth 1.5 beats (a crotchet plus half its value).',
                'Minim rest': 'A silence worth 2 beats.',
                'Crotchet rest': 'A silence worth 1 beat.',
                'Semibreve rest': 'A silence worth 4 beats.',
                'Two tied quavers': 'Two eighth notes joined together, worth 1 beat in total.',
            };
            const VALUES_NAMES = Object.keys(VALUES);

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
                storageKey: 'readingMusicNotationGame.settings',
                types: ['notes', 'values'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'notes') {
                        const item = NOTES[randInt(0, NOTES.length - 1)];
                        return { category: type, correctText: item.a, questionText: item.q };
                    }
                    const term = VALUES_NAMES[randInt(0, VALUES_NAMES.length - 1)];
                    return {
                        category: type,
                        label: term,
                        correctText: VALUES[term],
                        questionText: "What does a '" + term + "' mean?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'notes') {
                        const distractors = shuffle(
                            NOTES.map(function(f) { return f.a; }).filter(function(a) { return a !== q.correctText; })
                        ).slice(0, 3);
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(VALUES_NAMES, q.label, 3).map(function(t) { return VALUES[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'notes') {
                        return "Remember 'Every Good Boy Deserves Food' for the lines, and 'FACE' for the spaces.";
                    }
                    return 'Think about how many beats this note or rest lasts for.';
                },

                explanationFor: function(q) {
                    if (q.category === 'values') return q.label + ': ' + q.correctText;
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a music notation superstar!",
            });
        })();
    </script>
@endpush
