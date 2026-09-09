@extends('layouts.app')

@section('meta_title', 'Fiction Genres — English Game for Kids')
@section('meta_blurb', 'A free English game for kids — read a short description and work out the fiction genre.')
@section('meta_words', 'fiction genres game, book genres game for kids, reading game, ks2 english game, ks3 english game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-bookshelf',
        'title' => 'Fiction Genres',
        'subtitle' => 'Read the description, then pick the genre!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Fiction genres'],
        ],
        'aboutTitle' => 'About this fiction genres game',
        'aboutText' => 'This free English game helps you recognise fiction genres — mystery, fantasy, science fiction, adventure, horror, historical fiction, comedy and fairy tale — from a short description of the story.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const GENRES = ['Mystery', 'Fantasy', 'Science Fiction', 'Adventure', 'Horror', 'Historical Fiction', 'Comedy', 'Fairy Tale'];

            const DESCRIPTIONS = {
                'A detective must solve a crime by following clues.': 'Mystery',
                'Wizards, dragons and magic spells fill this imaginary world.': 'Fantasy',
                'A story set on a spaceship travelling to a distant planet.': 'Science Fiction',
                'Explorers battle danger to find hidden treasure.': 'Adventure',
                'A ghost haunts an old house, frightening everyone inside.': 'Horror',
                'The story is set during World War Two and follows real events.': 'Historical Fiction',
                'A silly mix-up leads to lots of jokes and laughter.': 'Comedy',
                'A princess, a wicked witch and a happy ending.': 'Fairy Tale',
                'A missing necklace leads to a series of clever clues.': 'Mystery',
                'A brave knight rides a dragon through an enchanted kingdom.': 'Fantasy',
                'Robots and aliens explore a future version of Earth.': 'Science Fiction',
                'A pirate crew sails the seas searching for buried gold.': 'Adventure',
            };
            const DESCRIPTION_NAMES = Object.keys(DESCRIPTIONS);

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
                storageKey: 'fiction-genresGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const description = DESCRIPTION_NAMES[randInt(0, DESCRIPTION_NAMES.length - 1)];
                    return {
                        category: type,
                        correctText: DESCRIPTIONS[description],
                        questionText: description + ' — which genre is this?',
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(GENRES, q.correctText, 3);
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about the setting, characters and mood the description describes.';
                },

                explanationFor: function(q) {
                    return 'This description fits the ' + q.correctText + ' genre.';
                },

                masteryMessage: "Amazing! You're a fiction genres superstar!",
            });
        })();
    </script>
@endpush
