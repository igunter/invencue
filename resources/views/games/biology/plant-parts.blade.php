@extends('layouts.app')

@section('meta_title', 'Plant Parts — Kids Biology Game')
@section('meta_blurb', 'A free biology game for young kids — learn what roots, stems, leaves, flowers and seeds do, and what plants need to grow.')
@section('meta_words', 'plant parts game, kids biology game, roots stem leaves flower seed, what plants need to grow')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-diagram-2',
        'title' => 'Plant Parts',
        'subtitle' => 'Pick your question types, then test your plant knowledge!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'parts', 'label' => 'What does it do?'],
            ['id' => 'needs', 'label' => 'What plants need'],
        ],
        'aboutTitle' => 'About this plant parts game',
        'aboutText' => 'This free biology game helps young kids learn what each part of a plant does — roots, stem, leaves, flower and seed — and what plants need to grow well. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const PARTS = {
                'Roots': 'Take in water and nutrients from the soil, and anchor the plant in place.',
                'Stem': 'Supports the plant and carries water up to the leaves.',
                'Leaves': 'Use sunlight to make food for the plant.',
                'Flower': 'Makes seeds so the plant can reproduce.',
                'Seed': 'Contains everything needed to grow into a new plant.',
            };
            const PART_NAMES = Object.keys(PARTS);

            const NEEDS = {
                'Sunlight': 'Gives plants the energy they need to make their own food.',
                'Water': 'Helps carry nutrients around the plant and keeps it from wilting.',
                'Soil': 'Provides nutrients and something for the roots to grip onto.',
                'Air': 'Provides the gas plants use, along with water and light, to make food.',
            };
            const NEED_NAMES = Object.keys(NEEDS);

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
                storageKey: 'plantPartsGame.settings',
                types: ['parts', 'needs'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'parts') {
                        const part = PART_NAMES[randInt(0, PART_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: PARTS[part],
                            questionText: 'What does the ' + part.toLowerCase() + ' do?',
                        };
                    }
                    const need = NEED_NAMES[randInt(0, NEED_NAMES.length - 1)];
                    return {
                        category: type,
                        label: need,
                        correctText: NEEDS[need],
                        questionText: 'Why do plants need ' + need.toLowerCase() + '?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'parts') {
                        const part = PART_NAMES.find(function(p) { return PARTS[p] === q.correctText; });
                        const distractors = pickOthers(PART_NAMES, part, 3).map(function(p) { return PARTS[p]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(NEED_NAMES, q.label, 3).map(function(n) { return NEEDS[n]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'parts') {
                        return 'Think about whether it is underground, holds the plant up, catches sunlight, or makes new plants.';
                    }
                    return 'Think about energy, nutrients, support, or the gas plants use to make food.';
                },

                explanationFor: function(q) {
                    if (q.category === 'needs') return q.label + ': ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a plant parts superstar!",
            });
        })();
    </script>
@endpush
