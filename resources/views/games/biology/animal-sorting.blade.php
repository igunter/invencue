@extends('layouts.app')

@section('meta_title', 'Animal Sorting — Kids Biology Game')
@section('meta_blurb', 'A free biology game for young kids — sort animals into groups: mammals, birds, fish, reptiles, insects and amphibians.')
@section('meta_words', 'animal sorting game, kids biology game, animal groups, mammals birds fish reptiles insects, classifying animals')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-collection',
        'title' => 'Animal Sorting',
        'subtitle' => 'Pick your question types, then sort those animals!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'sorting', 'label' => 'Which group?'],
            ['id' => 'features', 'label' => 'Group features'],
        ],
        'aboutTitle' => 'About this animal sorting game',
        'aboutText' => 'This free biology game helps young kids learn to sort animals into groups — mammals, birds, fish, reptiles, insects and amphibians — and what makes each group special. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const ANIMALS = {
                'Dog': 'Mammal',
                'Elephant': 'Mammal',
                'Eagle': 'Bird',
                'Penguin': 'Bird',
                'Shark': 'Fish',
                'Goldfish': 'Fish',
                'Snake': 'Reptile',
                'Lizard': 'Reptile',
                'Butterfly': 'Insect',
                'Bee': 'Insect',
                'Frog': 'Amphibian',
            };
            const ANIMAL_NAMES = Object.keys(ANIMALS);

            const GROUP_FEATURES = {
                'Mammal': 'Warm-blooded animals that usually have fur and feed their babies milk.',
                'Bird': 'Warm-blooded animals with feathers, wings and a beak.',
                'Fish': 'Cold-blooded animals that live in water and breathe through gills.',
                'Reptile': 'Cold-blooded animals with dry, scaly skin.',
                'Insect': 'Small animals with six legs and usually two pairs of wings.',
                'Amphibian': 'Animals that start life in water and can live on land as adults, like frogs.',
                'Arachnid': 'Small animals with eight legs and two body sections, like spiders.',
                'Crustacean': 'Animals with a hard outer shell that usually live in water, like crabs.',
                'Mollusc': 'Soft-bodied animals, often with a shell, like snails.',
                'Echinoderm': 'Spiny-skinned animals that live in the sea, like starfish.',
            };
            const GROUP_NAMES = Object.keys(GROUP_FEATURES);

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
                storageKey: 'animalSortingGame.settings',
                types: ['sorting', 'features'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'sorting') {
                        const animal = ANIMAL_NAMES[randInt(0, ANIMAL_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: ANIMALS[animal],
                            questionText: 'Which group does a ' + animal.toLowerCase() + ' belong to?',
                        };
                    }
                    const group = GROUP_NAMES[randInt(0, GROUP_NAMES.length - 1)];
                    return {
                        category: type,
                        label: group,
                        correctText: GROUP_FEATURES[group],
                        questionText: 'What is special about ' + group.toLowerCase() + 's?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'sorting') {
                        const distractors = pickOthers(GROUP_NAMES, q.correctText, 3);
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(GROUP_NAMES, q.label, 3).map(function(g) { return GROUP_FEATURES[g]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'sorting') {
                        return 'Think about whether it has fur, feathers, scales, six legs, or lives in water!';
                    }
                    return 'Think about its skin or covering, whether it lives in water, and how many legs it has.';
                },

                explanationFor: function(q) {
                    if (q.category === 'features') return q.label + ': ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're an animal sorting superstar!",
            });
        })();
    </script>
@endpush
