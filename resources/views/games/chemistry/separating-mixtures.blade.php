@extends('layouts.app')

@section('meta_title', 'Separating Mixtures — Kids Chemistry Game')
@section('meta_blurb', 'A free chemistry game for kids — pick the right method to separate a mixture, and learn how filtration, evaporation, distillation and chromatography work.')
@section('meta_words', 'separating mixtures game, kids chemistry game, filtration, evaporation, distillation, chromatography')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-funnel',
        'title' => 'Separating Mixtures',
        'subtitle' => 'Pick your question types, then test your separating skills!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'methods', 'label' => 'Pick the method'],
            ['id' => 'terms', 'label' => 'How does it work?'],
        ],
        'aboutTitle' => 'About this separating mixtures game',
        'aboutText' => 'This free chemistry game covers choosing the right method to separate different kinds of mixtures — filtration, evaporation, distillation, chromatography and using a magnet — and how each one actually works. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const METHODS = {
                'Separating sand from water': 'Filtration',
                'Separating salt from salt water': 'Evaporation',
                'Separating different coloured inks mixed together': 'Chromatography',
                'Separating alcohol from water (they have different boiling points)': 'Distillation',
                'Separating iron filings from sand': 'Using a magnet',
                'Separating tea leaves from brewed tea': 'Filtration',
                'Separating pure water from seawater': 'Distillation',
                'Separating dyes in a felt-tip pen ink spot': 'Chromatography',
                'Getting crystals of salt from rock salt after dissolving out the salt': 'Evaporation',
                'Separating steel paperclips from a pile of plastic buttons': 'Using a magnet',
            };
            const METHOD_SCENARIOS = Object.keys(METHODS);
            const METHOD_LIST = ['Filtration', 'Evaporation', 'Chromatography', 'Distillation', 'Using a magnet'];

            const TERMS = {
                'Filtration': 'Uses filter paper to separate an insoluble solid from a liquid.',
                'Evaporation': 'Heats a solution so the liquid turns to gas, leaving the dissolved solid behind.',
                'Distillation': 'Separates liquids with different boiling points by heating, then cooling and collecting the vapour.',
                'Chromatography': 'Separates substances, like inks, based on how well they dissolve and move through paper.',
                'Using a magnet': 'Attracts and pulls out magnetic materials, like iron or steel, from a mixture.',
                'Crystallisation': 'Slowly evaporates a solution so that pure, solid crystals form as the dissolved substance comes out of solution.',
                'Simple distillation': 'Separates a solvent from a solution by boiling off the liquid and then cooling and collecting the vapour, leaving the dissolved solid behind.',
                'Fractional distillation': 'Separates a mixture of liquids with different, close boiling points using a fractionating column, collecting each liquid separately.',
                'Sieving': 'Separates solids of different particle sizes by passing the mixture through a mesh with holes of a certain size.',
                'Decanting': 'Separates a liquid from a settled solid by carefully pouring off the liquid without disturbing the solid.',
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
                storageKey: 'separatingMixturesGame.settings',
                types: ['methods', 'terms'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'methods') {
                        const scenario = METHOD_SCENARIOS[randInt(0, METHOD_SCENARIOS.length - 1)];
                        return {
                            category: type,
                            correctText: METHODS[scenario],
                            questionText: scenario + ' — which method should you use?',
                        };
                    }
                    const term = TERM_NAMES[randInt(0, TERM_NAMES.length - 1)];
                    return {
                        category: type,
                        correctText: TERMS[term],
                        questionText: 'How does ' + term.toLowerCase() + ' work?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'methods') {
                        const distractors = pickOthers(METHOD_LIST, q.correctText, 3);
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const term = TERM_NAMES.find(function(t) { return TERMS[t] === q.correctText; });
                    const distractors = pickOthers(TERM_NAMES, term, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'methods') {
                        return 'Think about whether it involves a solid stuck in a liquid, something dissolved, different boiling points, colours, or magnetism.';
                    }
                    return 'Think about filter paper, heating to leave something behind, boiling points, or how far things travel on paper.';
                },

                explanationFor: function(q) {
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a separating mixtures superstar!",
            });
        })();
    </script>
@endpush
