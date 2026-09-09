@extends('layouts.app')

@section('meta_title', 'Practical Techniques — GCSE Science Lab Game')
@section('meta_blurb', 'A free GCSE science game matching lab equipment to its use, and covering required-practical techniques like titration, filtration and microscopy.')
@section('meta_words', 'practical techniques game, gcse science game, lab equipment, titration, filtration, microscopy, required practicals')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-funnel',
        'title' => 'Practical Techniques',
        'subtitle' => 'Pick your question types, then test your lab skills!',
        'typeToggles' => [
            ['id' => 'equipment', 'label' => 'Lab equipment'],
            ['id' => 'techniques', 'label' => 'Required practical techniques'],
        ],
        'aboutTitle' => 'About this practical techniques game',
        'aboutText' => 'This free GCSE science game covers what common lab equipment is used for, plus how the classic required-practical techniques work — titration, filtration, microscopy and measuring gas produced. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const EQUIPMENT = {
                'Bunsen burner': 'Used to heat substances directly with a flame.',
                'Measuring cylinder': 'Used to measure the volume of a liquid.',
                'Burette': 'Used to add a precise, variable volume of liquid drop by drop — key for titrations.',
                'Thermometer': 'Used to measure temperature.',
                'Microscope': 'Used to view very small objects or cells, magnified.',
                'Balance': 'Used to measure the mass of a substance.',
            };
            const EQUIPMENT_NAMES = Object.keys(EQUIPMENT);

            const TECHNIQUES = {
                'Titration': 'A technique used to find the exact volume of one solution needed to react completely with another, using a burette and an indicator.',
                'Filtration': 'A technique used to separate an insoluble solid from a liquid, using filter paper.',
                'Using a microscope': 'Focus on low power first, then increase magnification to see more detail once the sample is in view.',
                'Measuring gas produced': 'Often measured with a gas syringe, or by collecting the gas in an upside-down measuring cylinder over water.',
            };
            const TECHNIQUE_NAMES = Object.keys(TECHNIQUES);

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
                storageKey: 'practicalTechniquesGame.settings',
                types: ['equipment', 'techniques'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const pool = type === 'equipment' ? EQUIPMENT : TECHNIQUES;
                    const names = type === 'equipment' ? EQUIPMENT_NAMES : TECHNIQUE_NAMES;
                    const key = names[randInt(0, names.length - 1)];
                    return {
                        category: type,
                        label: key,
                        correctText: pool[key],
                        questionText: type === 'equipment'
                            ? "What is a '" + key + "' used for?"
                            : "How does '" + key + "' work?",
                    };
                },

                buildChoices: function(q) {
                    const pool = q.category === 'equipment' ? EQUIPMENT : TECHNIQUES;
                    const names = q.category === 'equipment' ? EQUIPMENT_NAMES : TECHNIQUE_NAMES;
                    const distractors = pickOthers(names, q.label, 3).map(function(k) { return pool[k]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'equipment') {
                        return 'Think about whether this measures something, holds something, or changes something (like heating it).';
                    }
                    return 'Think about what problem this technique solves — separating something, measuring something precisely, or viewing something too small to see.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered practical techniques!",
            });
        })();
    </script>
@endpush
