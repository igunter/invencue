@extends('layouts.app')

@section('meta_title', 'Classification — Kids Biology Game')
@section('meta_blurb', 'A free biology game for kids — sort living things into kingdoms and learn classification vocabulary like species, vertebrate and invertebrate.')
@section('meta_words', 'classification game, kids biology game, kingdoms, species, vertebrate, invertebrate, taxonomy')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-collection',
        'title' => 'Classification',
        'subtitle' => 'Pick your question types, then test your classification knowledge!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'kingdoms', 'label' => 'Which kingdom?'],
            ['id' => 'terms', 'label' => 'Key terms'],
        ],
        'aboutTitle' => 'About this classification game',
        'aboutText' => 'This free biology game covers sorting living things into the five kingdoms — animal, plant, fungi, protist and bacteria — plus classification vocabulary like species, vertebrate, invertebrate and taxonomy. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const ORGANISMS = {
                'Dog': 'Animal kingdom',
                'Oak tree': 'Plant kingdom',
                'Mushroom': 'Fungi kingdom',
                'Amoeba': 'Protist kingdom',
                'E. coli bacteria': 'Bacteria kingdom',
                'Lion': 'Animal kingdom',
                'Sunflower': 'Plant kingdom',
                'Yeast': 'Fungi kingdom',
                'Algae': 'Protist kingdom',
                'Salmonella bacteria': 'Bacteria kingdom',
            };
            const ORGANISM_NAMES = Object.keys(ORGANISMS);
            const KINGDOM_LIST = ['Animal kingdom', 'Plant kingdom', 'Fungi kingdom', 'Protist kingdom', 'Bacteria kingdom'];

            const TERMS = {
                'Species': 'A group of organisms that can breed together to produce fertile offspring.',
                'Classification': 'Sorting living things into groups based on their similarities and differences.',
                'Vertebrate': 'An animal with a backbone, such as a fish, bird or mammal.',
                'Invertebrate': 'An animal without a backbone, such as an insect or worm.',
                'Taxonomy': 'The scientific naming and classification of living things.',
                'Kingdom': 'One of the largest groups used to classify living things, such as animal or plant.',
                'Genus': 'A group of closely related species, used in an organism\'s scientific name.',
                'Binomial naming': 'The system of giving each species a two-part scientific name, made of genus and species.',
                'Characteristic': 'A feature of an organism that can be used to help classify it.',
                'Habitat': 'The natural home or environment where an organism lives.',
            };
            const TERM_NAMES = Object.keys(TERMS);

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
                storageKey: 'classificationGame.settings',
                types: ['kingdoms', 'terms'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'kingdoms') {
                        const organism = ORGANISM_NAMES[randInt(0, ORGANISM_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: ORGANISMS[organism],
                            questionText: 'Which kingdom does a ' + organism.toLowerCase() + ' belong to?',
                        };
                    }
                    const term = TERM_NAMES[randInt(0, TERM_NAMES.length - 1)];
                    return {
                        category: type,
                        correctText: TERMS[term],
                        questionText: "What does '" + term + "' mean?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'kingdoms') {
                        const distractors = pickOthers(KINGDOM_LIST, q.correctText, 3);
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const term = TERM_NAMES.find(function(t) { return TERMS[t] === q.correctText; });
                    const distractors = pickOthers(TERM_NAMES, term, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'kingdoms') {
                        return 'Think about whether it is a plant, an animal, a single-celled organism, a mould-like organism, or a tiny microbe.';
                    }
                    return 'Think about backbones, breeding groups, sorting, or scientific naming.';
                },

                explanationFor: function(q) {
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a classification superstar!",
            });
        })();
    </script>
@endpush
