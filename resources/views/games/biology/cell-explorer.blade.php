@extends('layouts.app')

@section('meta_title', 'Cell Explorer — Kids Biology Game')
@section('meta_blurb', 'A free biology game for kids — learn what the parts of a cell do, and whether a feature belongs to plant cells, animal cells, or both.')
@section('meta_words', 'cell explorer game, kids biology game, plant cell, animal cell, nucleus, mitochondria, cell wall, chloroplast')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-grid-3x3-gap',
        'title' => 'Cell Explorer',
        'subtitle' => 'Pick your question types, then explore those cells!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'organelles', 'label' => 'Cell parts'],
            ['id' => 'whichCell', 'label' => 'Plant, animal or both?'],
        ],
        'aboutTitle' => 'About this cell explorer game',
        'aboutText' => 'This free biology game covers what each part of a cell does — nucleus, cell membrane, cytoplasm, mitochondria and ribosomes — plus which features belong to plant cells, animal cells, or both. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const ORGANELLES = {
                'Nucleus': 'Contains DNA and controls the activities of the cell.',
                'Cell membrane': 'Controls which substances enter and leave the cell.',
                'Cytoplasm': 'A jelly-like substance where chemical reactions happen.',
                'Mitochondria': 'The site of respiration, releasing energy for the cell.',
                'Ribosomes': 'Where proteins are made in the cell.',
                'Cell wall': 'A rigid layer outside the cell membrane that supports and protects plant cells.',
                'Chloroplast': 'Contains chlorophyll and is the site of photosynthesis in plant cells.',
                'Vacuole': 'A fluid-filled space that stores water and helps keep the cell firm.',
                'Golgi apparatus': 'Processes and packages proteins before they leave the cell.',
                'Endoplasmic reticulum': 'A network of membranes that transports proteins and other materials around the cell.',
            };
            const ORGANELLE_NAMES = Object.keys(ORGANELLES);

            const CHOICES_FIXED = ['Plant cells only', 'Animal cells only', 'Both plant and animal cells', 'Neither plant nor animal cells'];

            const FEATURES = {
                'A cell wall': 'Plant cells only',
                'A chloroplast': 'Plant cells only',
                'A large, permanent vacuole': 'Plant cells only',
                'A nucleus': 'Both plant and animal cells',
                'Mitochondria': 'Both plant and animal cells',
                'A cell membrane': 'Both plant and animal cells',
                'A flexible, irregular shape': 'Animal cells only',
                'Centrioles, used in cell division': 'Animal cells only',
                'Chlorophyll': 'Plant cells only',
                'Ribosomes': 'Both plant and animal cells',
                'A small vacuole (if present)': 'Animal cells only',
                'A round, regular shape': 'Animal cells only',
                'A rigid, fixed shape': 'Plant cells only',
                'Cytoplasm': 'Both plant and animal cells',
            };
            const FEATURE_NAMES = Object.keys(FEATURES);

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
                storageKey: 'cellExplorerGame.settings',
                types: ['organelles', 'whichCell'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'organelles') {
                        const part = ORGANELLE_NAMES[randInt(0, ORGANELLE_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: ORGANELLES[part],
                            questionText: 'What does the ' + part.toLowerCase() + ' do?',
                        };
                    }
                    const feature = FEATURE_NAMES[randInt(0, FEATURE_NAMES.length - 1)];
                    return {
                        category: type,
                        label: feature,
                        correctText: FEATURES[feature],
                        questionText: 'Which cells have ' + feature.toLowerCase() + '?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'whichCell') {
                        return shuffle(CHOICES_FIXED);
                    }
                    const part = ORGANELLE_NAMES.find(function(p) { return ORGANELLES[p] === q.correctText; });
                    const distractors = pickOthers(ORGANELLE_NAMES, part, 3).map(function(p) { return ORGANELLES[p]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'whichCell') {
                        return 'Chloroplasts, cell walls and big vacuoles are the plant-only clues. Everything else in the list is shared by both.';
                    }
                    return 'Think about control, energy, protein-making, or a barrier around the cell.';
                },

                explanationFor: function(q) {
                    if (q.category === 'whichCell') return q.label + ': ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a cell explorer superstar!",
            });
        })();
    </script>
@endpush
