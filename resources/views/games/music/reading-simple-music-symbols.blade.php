@extends('layouts.app')

@section('meta_title', 'Reading Simple Music Symbols — Music Game for Kids')
@section('meta_blurb', 'A free music game for young kids — learn to tell a note symbol from a rest symbol, and simple symbol meanings.')
@section('meta_words', 'music symbols game, note or rest quiz, reading music for kids, music notation basics, ks1 ks2 music game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-music-note',
        'title' => 'Reading Simple Music Symbols',
        'subtitle' => 'Pick your question types, then read those symbols!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'identify', 'label' => 'Note or rest?'],
            ['id' => 'meaning', 'label' => 'What does it mean?'],
        ],
        'aboutTitle' => 'About this reading simple music symbols game',
        'aboutText' => 'This free music game helps young kids start reading music by telling note symbols apart from rest symbols, and learning what simple symbols on the stave mean. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const IDENTIFY = {
                'A crotchet (quarter note)': 'Note',
                'A crotchet rest': 'Rest',
                'A minim (half note)': 'Note',
                'A minim rest': 'Rest',
                'A semibreve (whole note)': 'Note',
                'A semibreve rest': 'Rest',
                'A quaver (eighth note)': 'Note',
                'A quaver rest': 'Rest',
                'A semiquaver (sixteenth note)': 'Note',
                'A semiquaver rest': 'Rest',
            };
            const IDENTIFY_NAMES = Object.keys(IDENTIFY);

            const MEANING = {
                'Treble clef': 'A symbol at the start of the stave showing higher-pitched notes.',
                'Bass clef': 'A symbol at the start of the stave showing lower-pitched notes.',
                'Stave (or staff)': 'The five lines that music notes are written on.',
                'Bar line': 'A vertical line that divides music into bars.',
                'Time signature': 'The two numbers at the start of a piece showing how many beats are in each bar.',
                'Rest symbol': 'A symbol showing a moment of silence in the music.',
                'Note head': 'The oval part of a note that shows its pitch.',
                'Stem': 'The line attached to a note head that helps show its length.',
                'Sharp sign (♯)': 'A symbol that raises a note by a semitone.',
                'Flat sign (♭)': 'A symbol that lowers a note by a semitone.',
            };
            const MEANING_NAMES = Object.keys(MEANING);

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
                storageKey: 'readingSimpleMusicSymbolsGame.settings',
                types: ['identify', 'meaning'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'identify') {
                        const item = IDENTIFY_NAMES[randInt(0, IDENTIFY_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: IDENTIFY[item],
                            questionText: item + ' — is this a note or a rest?',
                        };
                    }
                    const term = MEANING_NAMES[randInt(0, MEANING_NAMES.length - 1)];
                    return {
                        category: type,
                        label: term,
                        correctText: MEANING[term],
                        questionText: "What does '" + term + "' mean?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'identify') {
                        return shuffle(['Note', 'Rest']);
                    }
                    const distractors = pickOthers(MEANING_NAMES, q.label, 3).map(function(t) { return MEANING[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'identify') {
                        return "Does this symbol tell you to play a sound, or to stay silent?";
                    }
                    return 'Think about where this symbol appears on the stave and what job it does.';
                },

                explanationFor: function(q) {
                    if (q.category === 'meaning') return q.label + ': ' + q.correctText;
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a music symbols superstar!",
            });
        })();
    </script>
@endpush
