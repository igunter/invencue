@extends('layouts.app')

@section('meta_title', 'Rhythm & Beat Basics — Music Game for Kids')
@section('meta_blurb', 'A free music game for young kids — learn what a beat is, simple rhythm patterns and clapping games.')
@section('meta_words', 'rhythm game for kids, beat game, clapping patterns, music game for kids, ks1 ks2 music game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-soundwave',
        'title' => 'Rhythm & Beat Basics',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Rhythm & beat facts'],
        ],
        'aboutTitle' => 'About this rhythm & beat basics game',
        'aboutText' => 'This free music game helps young kids understand what a beat is, how rhythm patterns work, and simple ideas like clapping along and keeping the pulse of a song.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What do we call the steady pulse you can tap or clap along to in music?", a: "The beat" },
                { q: "What is it called when sounds are arranged into a pattern of long and short notes?", a: "Rhythm" },
                { q: "What is a group of beats counted together in music called?", a: "A bar" },
                { q: "If you clap once on every beat while a song plays, what are you doing?", a: "Keeping the beat" },
                { q: "What do we call a short rhythm pattern that repeats over and over?", a: "An ostinato" },
                { q: "What word describes music with no steady beat at all?", a: "Free rhythm" },
                { q: "If a note lasts for two beats, is it longer or shorter than a note lasting one beat?", a: "Longer" },
                { q: "What might a teacher or drummer use to help everyone keep the same steady beat?", a: "A metronome" },
                { q: "What do we call the very first, strongest beat in a bar?", a: "The downbeat" },
                { q: "If you tap your foot along to a song, what are you following?", a: "The beat" },
                { q: "What do we call it when two different rhythms are clapped at the same time?", a: "A cross-rhythm" },
                { q: "Which instrument family is often used to keep the beat in a band?", a: "Percussion" },
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
                storageKey: 'rhythmBeatBasicsGame.settings',
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
                    return 'Think about clapping along to a song and what keeps everyone together.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a rhythm and beat superstar!",
            });
        })();
    </script>
@endpush
