@extends('layouts.app')

@section('meta_title', 'Singing & Voice Basics — Music Game for Kids')
@section('meta_blurb', 'A free music game for young kids — simple facts about how we sing and use our voices.')
@section('meta_words', 'singing game for kids, voice basics, music game for kids, choir facts, ks1 ks2 music game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-mic',
        'title' => 'Singing & Voice Basics',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Singing & voice facts'],
        ],
        'aboutTitle' => 'About this singing & voice basics game',
        'aboutText' => 'This free music game helps young kids learn simple facts about how we sing and use our voices, from warming up and breathing well to singing together in a choir.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "What do we call the part in your throat that helps you make sound when you sing?", a: "The voice box" },
                { q: "What should you do before singing to help your voice work well?", a: "Warm up" },
                { q: "What do singers do to fill their lungs with air before a long phrase?", a: "Take a deep breath" },
                { q: "What word describes a voice that sings quite high notes, often used by children?", a: "A high voice" },
                { q: "What word describes a voice that sings quite low notes, often heard in adult men?", a: "A low voice" },
                { q: "What do we call a group of people singing together in different voice parts?", a: "A choir" },
                { q: "What should you do with your posture to help you sing well?", a: "Stand up tall" },
                { q: "What do we call it when one singer sings a tune completely alone?", a: "A solo" },
                { q: "What do we call it when everyone sings the exact same tune together?", a: "Unison" },
                { q: "What might happen to your voice if you shout too much and don't rest it?", a: "It could become hoarse" },
                { q: "What do singers do with their mouths to make the words sound clear?", a: "Enunciate clearly" },
                { q: "What do we call breathing in deeply to support your singing?", a: "Breath support" },
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
                storageKey: 'singingVoiceBasicsGame.settings',
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
                    return 'Think about what a singer does with their breath, body and voice.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a singing and voice superstar!",
            });
        })();
    </script>
@endpush
