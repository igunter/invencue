@extends('layouts.app')

@section('meta_title', 'Musical Families — Music Game for Kids')
@section('meta_blurb', 'A free music game for young kids — sort instruments into the string, wind and percussion families.')
@section('meta_words', 'musical families game, instrument families for kids, string wind percussion, music game for kids, ks1 ks2 music game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-collection',
        'title' => 'Musical Families',
        'subtitle' => 'Which family does it belong to?',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'family', 'label' => 'Which family?'],
        ],
        'aboutTitle' => 'About this musical families game',
        'aboutText' => 'This free music game helps young kids group common instruments into the string, wind and percussion families, an early step towards learning about the orchestra.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FAMILY = {
                'Violin': 'String',
                'Guitar': 'String',
                'Cello': 'String',
                'Harp': 'String',
                'Flute': 'Wind',
                'Trumpet': 'Wind',
                'Recorder': 'Wind',
                'Clarinet': 'Wind',
                'Drums': 'Percussion',
                'Xylophone': 'Percussion',
                'Tambourine': 'Percussion',
                'Triangle': 'Percussion',
            };
            const FAMILY_NAMES = Object.keys(FAMILY);
            const OPTIONS = ['String', 'Wind', 'Percussion'];

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
                storageKey: 'musicalFamiliesGame.settings',
                types: ['family'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const instrument = FAMILY_NAMES[randInt(0, FAMILY_NAMES.length - 1)];
                    return {
                        category: type,
                        correctText: FAMILY[instrument],
                        questionText: instrument + ' — which family does it belong to?',
                    };
                },

                buildChoices: function() {
                    return shuffle(OPTIONS.slice());
                },

                hintFor: function() {
                    return 'Is it plucked or bowed, blown into, or hit and shaken?';
                },

                explanationFor: function(q) {
                    return q.correctText + ' family.';
                },

                masteryMessage: "Amazing! You're a musical families superstar!",
            });
        })();
    </script>
@endpush
