@extends('layouts.app')

@section('meta_title', 'Animal Adaptations — Kids Biology Game')
@section('meta_blurb', 'A free biology game for kids — match animals to the adaptation that helps them survive, and learn the biggest challenge of each habitat.')
@section('meta_words', 'animal adaptations game, kids biology game, habitats, camouflage, desert arctic rainforest ocean survival')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-compass',
        'title' => 'Animal Adaptations',
        'subtitle' => 'Pick your question types, then test your survival knowledge!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'adaptations', 'label' => 'Animal adaptations'],
            ['id' => 'habitats', 'label' => 'Habitat challenges'],
        ],
        'aboutTitle' => 'About this animal adaptations game',
        'aboutText' => 'This free biology game covers how animals like polar bears, camels, cacti and giraffes are adapted to survive, plus the biggest survival challenge in habitats like the desert, arctic, rainforest and ocean depths. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const ADAPTATIONS = {
                'Polar bear': 'Thick white fur for camouflage and insulation in the snow.',
                'Camel': 'Humps that store fat for energy, and long legs to stay away from hot sand.',
                'Cactus': 'Thick, waxy skin and spines to store water and prevent water loss.',
                'Giraffe': "A long neck to reach leaves high up in trees that other animals can't.",
                'Chameleon': 'Colour-changing skin to hide from predators.',
                'Duck': 'Webbed feet for swimming efficiently in water.',
            };
            const ADAPTATION_NAMES = Object.keys(ADAPTATIONS);

            const HABITATS = {
                'Desert': 'Very little water and extreme heat during the day.',
                'Arctic (polar region)': 'Extreme cold and long periods of darkness in winter.',
                'Rainforest': 'Intense competition for light, and heavy rainfall.',
                'Ocean depths': 'Total darkness and very high water pressure.',
            };
            const HABITAT_NAMES = Object.keys(HABITATS);

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
                storageKey: 'animalAdaptationsGame.settings',
                types: ['adaptations', 'habitats'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'adaptations') {
                        const animal = ADAPTATION_NAMES[randInt(0, ADAPTATION_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: ADAPTATIONS[animal],
                            questionText: 'How is a ' + animal.toLowerCase() + ' adapted to survive?',
                        };
                    }
                    const habitat = HABITAT_NAMES[randInt(0, HABITAT_NAMES.length - 1)];
                    return {
                        category: type,
                        label: habitat,
                        correctText: HABITATS[habitat],
                        questionText: "What is the biggest survival challenge in the " + habitat.toLowerCase() + "?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'adaptations') {
                        const animal = ADAPTATION_NAMES.find(function(a) { return ADAPTATIONS[a] === q.correctText; });
                        const distractors = pickOthers(ADAPTATION_NAMES, animal, 3).map(function(a) { return ADAPTATIONS[a]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(HABITAT_NAMES, q.label, 3).map(function(h) { return HABITATS[h]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'adaptations') {
                        return 'Think about the fur, skin, shape or body part that helps this animal deal with its environment.';
                    }
                    return 'Think about temperature, water, light and pressure — which one is most extreme there?';
                },

                explanationFor: function(q) {
                    if (q.category === 'habitats') return q.label + ': ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're an animal adaptations superstar!",
            });
        })();
    </script>
@endpush
