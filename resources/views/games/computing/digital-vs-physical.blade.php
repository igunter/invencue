@extends('layouts.app')

@section('meta_title', 'Digital vs Physical — Computing Game for Kids')
@section('meta_blurb', 'A free computing game for kids — sort things that are on a screen (digital) from real-world objects (physical).')
@section('meta_words', 'digital vs physical game, kids computing game, digital or physical quiz, ks1 ks2 computing')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-collection',
        'title' => 'Digital vs Physical',
        'subtitle' => 'Read the item, then decide — digital or physical?',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Digital or physical?'],
        ],
        'aboutTitle' => 'About this digital vs physical game',
        'aboutText' => 'This free computing game helps young kids tell the difference between digital things that only exist on a screen and physical things they can hold in their hands.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const ITEMS = {
                'A photo printed and kept in a photo album': 'Physical',
                'A photo saved on a tablet': 'Digital',
                'A book made of paper pages': 'Physical',
                'An e-book read on a screen': 'Digital',
                'A letter sent by post': 'Physical',
                'An email sent to a friend': 'Digital',
                'Money in a piggy bank': 'Physical',
                'Money shown in a banking app': 'Digital',
                'A song played from a CD': 'Physical',
                'A song streamed from an app': 'Digital',
                'A drawing made with pencil and paper': 'Physical',
                'A drawing made using a paint app': 'Digital',
            };
            const ITEM_NAMES = Object.keys(ITEMS);

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
                storageKey: 'digitalVsPhysicalGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const item = ITEM_NAMES[randInt(0, ITEM_NAMES.length - 1)];
                    return {
                        category: type,
                        correctText: ITEMS[item],
                        questionText: item + ' — is this digital or physical?',
                    };
                },

                buildChoices: function(q) {
                    return shuffle(['Digital', 'Physical']);
                },

                hintFor: function() {
                    return 'Can you hold it in your hands, or is it only on a screen?';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a digital vs physical superstar!",
            });
        })();
    </script>
@endpush
