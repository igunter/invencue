@extends('layouts.app')

@section('meta_title', 'Healthy Eating — Kids Biology Game')
@section('meta_blurb', 'A free biology game for young kids — sort foods into the right food group and learn why each group is good for you.')
@section('meta_words', 'healthy eating game, kids biology game, food groups, balanced diet, fruits vegetables carbohydrates protein dairy')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-cookie',
        'title' => 'Healthy Eating',
        'subtitle' => 'Pick your question types, then sort those foods!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'groups', 'label' => 'Which food group?'],
            ['id' => 'facts', 'label' => 'Why is it healthy?'],
        ],
        'aboutTitle' => 'About this healthy eating game',
        'aboutText' => 'This free biology game helps young kids learn to sort foods into the right food group — fruits & vegetables, carbohydrates, protein, dairy, and fats & sugars — and why each group is good for a balanced diet. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FOODS = {
                'Apple': 'Fruits & vegetables',
                'Carrot': 'Fruits & vegetables',
                'Bread': 'Carbohydrates',
                'Pasta': 'Carbohydrates',
                'Chicken': 'Protein',
                'Beans': 'Protein',
                'Cheese': 'Dairy',
                'Milk': 'Dairy',
                'Butter': 'Fats & sugars',
                'Sweets': 'Fats & sugars',
            };
            const FOOD_NAMES = Object.keys(FOODS);

            const GROUP_FACTS = {
                'Fruits & vegetables': 'Give your body vitamins and fibre to help you stay healthy.',
                'Carbohydrates': 'Give your body energy to run and play.',
                'Protein': 'Helps your body grow and repair itself.',
                'Dairy': 'Gives your body calcium for strong bones and teeth.',
                'Fats & sugars': 'Give quick energy but should only be eaten in small amounts.',
                'Water & fluids': 'Keeps your body hydrated and helps it work properly.',
                'Wholegrains': 'Give you fibre and energy that lasts longer than sugary foods.',
                'Oily fish': 'Gives you healthy fats that are good for your heart and brain.',
                'Nuts & seeds': 'Give you healthy fats, protein and vitamins.',
                'Iron-rich foods': 'Help your body make healthy red blood cells.',
            };
            const GROUP_NAMES = Object.keys(GROUP_FACTS);

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
                storageKey: 'healthyEatingGame.settings',
                types: ['groups', 'facts'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'groups') {
                        const food = FOOD_NAMES[randInt(0, FOOD_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: FOODS[food],
                            questionText: 'Which food group does ' + food.toLowerCase() + ' belong to?',
                        };
                    }
                    const group = GROUP_NAMES[randInt(0, GROUP_NAMES.length - 1)];
                    return {
                        category: type,
                        label: group,
                        correctText: GROUP_FACTS[group],
                        questionText: 'Why is ' + group.toLowerCase() + ' good for you?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'groups') {
                        const distractors = pickOthers(GROUP_NAMES, q.correctText, 3);
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(GROUP_NAMES, q.label, 3).map(function(g) { return GROUP_FACTS[g]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'groups') {
                        return 'Think about whether it grows in the ground, comes from an animal, or is a grain.';
                    }
                    return 'Think about energy, growing and repairing, strong bones, or vitamins.';
                },

                explanationFor: function(q) {
                    if (q.category === 'facts') return q.label + ': ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a healthy eating superstar!",
            });
        })();
    </script>
@endpush
