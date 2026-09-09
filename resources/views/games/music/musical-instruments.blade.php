@extends('layouts.app')

@section('meta_title', 'Musical Instruments — Music Game for Kids')
@section('meta_blurb', 'A free music game for young kids — learn to recognise common musical instruments from simple clues.')
@section('meta_words', 'musical instruments game, music game for kids, instrument quiz, learn instruments, ks1 ks2 music game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-music-note-beamed',
        'title' => 'Musical Instruments',
        'subtitle' => 'Read the clue, then work out the instrument!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Instrument facts'],
        ],
        'aboutTitle' => 'About this musical instruments game',
        'aboutText' => 'This free music game helps young kids learn to recognise common musical instruments, from the piano and violin to the drums and trumpet, using simple clues about how each one looks and sounds.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "Which instrument has black and white keys you press with your fingers?", a: "Piano" },
                { q: "Which instrument has strings and is played with a bow?", a: "Violin" },
                { q: "Which instrument do you blow into and press keys on, often made of silver?", a: "Flute" },
                { q: "Which loud brass instrument has a long sliding tube?", a: "Trombone" },
                { q: "Which instrument do you hit with sticks to make a beat?", a: "Drums" },
                { q: "Which instrument has six strings and is often strummed with your fingers?", a: "Guitar" },
                { q: "Which curly brass instrument is held with one hand inside its bell?", a: "French horn" },
                { q: "Which woodwind instrument uses a single reed and has a black body?", a: "Clarinet" },
                { q: "Which large stringed instrument do you play sitting down, resting it on the floor?", a: "Cello" },
                { q: "Which small, shiny brass instrument has three valves you press?", a: "Trumpet" },
                { q: "Which set of metal bars do you hit with mallets to play a tune?", a: "Xylophone" },
                { q: "Which giant stringed instrument is so big you stand or sit on a stool to play it?", a: "Double bass" },
            ];

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
                storageKey: 'musicalInstrumentsGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const item = FACTS[randInt(0, FACTS.length - 1)];
                    return { category: type, correctText: item.a, questionText: item.q };
                },

                buildChoices: function(q) {
                    const distractors = shuffle(
                        FACTS.map(function(f) { return f.a; }).filter(function(a) { return a !== q.correctText; })
                    ).slice(0, 3);
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about how the instrument is played and what it looks like.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a musical instruments superstar!",
            });
        })();
    </script>
@endpush
