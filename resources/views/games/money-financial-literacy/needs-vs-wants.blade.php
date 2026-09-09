@extends('layouts.app')

@section('meta_title', 'Needs vs Wants — Money Game for Kids')
@section('meta_blurb', 'A free money game for young kids — sort everyday things into needs and wants and learn the difference.')
@section('meta_words', 'needs vs wants game, money game for kids, financial literacy for kids, ks1 money game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-basket',
        'title' => 'Needs vs Wants',
        'subtitle' => 'Read the item, then decide if it\'s a need or a want!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Need or want?'],
        ],
        'aboutTitle' => 'About this needs vs wants game',
        'aboutText' => 'This free money game helps young kids learn the difference between needs — things we must have to live, like food, water and a home — and wants — things that are nice to have but we could live without. Choose your question count and time limit, then sort as many items as you can.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const ITEMS = {
                'Food to eat': 'Need',
                'A place to live': 'Need',
                'Clean water to drink': 'Need',
                'Clothes to keep you warm': 'Need',
                'Going to school': 'Need',
                'Medicine when you are ill': 'Need',
                'A bicycle that is your only way to get to school': 'Need',
                'A new video game': 'Want',
                'The latest trainers': 'Want',
                'A trip to a theme park': 'Want',
                'A bar of chocolate': 'Want',
                'A toy you saw in a shop': 'Want',
                'A second phone, just because it is a new colour': 'Want',
                'Extra sweets after you have already had a treat': 'Want',
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
                storageKey: 'needsVsWantsGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const item = ITEM_NAMES[randInt(0, ITEM_NAMES.length - 1)];
                    return {
                        category: type,
                        label: item,
                        correctText: ITEMS[item],
                        questionText: item + ' — is this a need or a want?',
                    };
                },

                buildChoices: function() {
                    return shuffle(['Need', 'Want']);
                },

                hintFor: function() {
                    return 'A need is something you must have to survive. A want is something nice to have, but you could live without it.';
                },

                explanationFor: function(q) {
                    return q.label + ' is a ' + q.correctText.toLowerCase() + '.';
                },

                masteryMessage: "Amazing! You really know your needs from your wants!",
            });
        })();
    </script>
@endpush
