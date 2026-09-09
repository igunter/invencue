@extends('layouts.app')

@section('meta_title', 'Photosynthesis & Respiration — GCSE Biology Game')
@section('meta_blurb', 'A free GCSE biology game comparing photosynthesis and respiration — reactants, products, location, energy changes and equations.')
@section('meta_words', 'photosynthesis game, respiration game, gcse biology game, cellular respiration, aerobic respiration, biology revision game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-arrow-left-right',
        'title' => 'Photosynthesis & Respiration',
        'subtitle' => 'Pick your question types, then tell the two processes apart!',
        'typeToggles' => [
            ['id' => 'reactants', 'label' => 'Reactants'],
            ['id' => 'products', 'label' => 'Products'],
            ['id' => 'location', 'label' => 'Location in the cell'],
            ['id' => 'energyType', 'label' => 'Energy type'],
            ['id' => 'condition', 'label' => 'Key facts'],
            ['id' => 'equation', 'label' => 'Equations'],
            ['id' => 'occurs', 'label' => 'When it happens'],
        ],
        'aboutTitle' => 'About this photosynthesis & respiration game',
        'aboutText' => 'This free GCSE biology game tests the classic exam topic students mix up most — photosynthesis versus respiration. Choose which question types to include (reactants, products, location, energy type, key facts, equations and when each process happens), set your question count and time limit, then see how many you can get right. Every wrong answer includes a quick explanation to help it stick.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const PROCESSES = ['photosynthesis', 'respiration'];

            const FACTS = {
                photosynthesis: {
                    label: 'Photosynthesis',
                    reactants: 'Carbon dioxide and water',
                    products: 'Glucose and oxygen',
                    location: 'Chloroplasts',
                    energyType: 'Endothermic — takes in energy',
                    condition: 'Needs light energy to happen',
                    equation: '6CO₂ + 6H₂O → C₆H₁₂O₆ + 6O₂',
                    occurs: 'Only when light is available',
                },
                respiration: {
                    label: 'Respiration',
                    reactants: 'Glucose and oxygen',
                    products: 'Carbon dioxide and water',
                    location: 'Mitochondria',
                    energyType: 'Exothermic — releases energy',
                    condition: 'Happens all the time, even in the dark',
                    equation: 'C₆H₁₂O₆ + 6O₂ → 6CO₂ + 6H₂O',
                    occurs: 'Constantly, day and night, in every living cell',
                },
            };

            const EXTRA_DISTRACTORS = {
                reactants: ['Glucose and carbon dioxide', 'Oxygen and water'],
                products: ['Glucose and water', 'Nitrogen and oxygen'],
                location: ['Nucleus', 'Cell membrane'],
                energyType: ['Neither — there is no energy change', 'Both exothermic and endothermic'],
                condition: ['Only happens in animal cells', 'Only happens in the roots of a plant'],
                equation: ['6CO₂ + 6O₂ → C₆H₁₂O₆ + 6H₂O', 'C₆H₁₂O₆ + 6H₂O → 6CO₂ + 6O₂'],
                occurs: ['Only during the winter months', 'Only in cells that are dying'],
            };

            const QUESTION_TEXT = {
                reactants: 'What are the reactants (starting substances) of PROCESS?',
                products: 'What are the products of PROCESS?',
                location: 'Where in the cell does PROCESS mainly take place?',
                energyType: 'Is PROCESS exothermic or endothermic?',
                condition: 'Which of these is true about PROCESS?',
                equation: 'Which equation correctly represents PROCESS?',
                occurs: 'When does PROCESS happen?',
            };

            const HINTS = {
                reactants: 'Reactants go IN to a reaction — think about what a plant pulls from the air and soil, versus what a cell uses up to release energy.',
                products: 'Products come OUT of a reaction. Photosynthesis makes food and a gas; respiration makes a waste gas and water.',
                location: 'Chloroplasts are only found in plant and algal cells, and contain chlorophyll. Mitochondria are found in almost every living cell.',
                energyType: 'Exothermic reactions release energy to the surroundings; endothermic reactions take energy in.',
                condition: 'Photosynthesis depends on light. Respiration doesn’t need light and never stops.',
                equation: 'Check which side of the arrow has glucose and oxygen, and which side has carbon dioxide and water.',
                occurs: 'Photosynthesis can only happen when there is light. Respiration happens all the time, in every living cell.',
            };

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

            function otherProcess(process) {
                return process === 'photosynthesis' ? 'respiration' : 'photosynthesis';
            }

            window.ScienceQuiz.run({
                storageKey: 'photosynthesisRespirationGame.settings',
                types: ['reactants', 'products', 'location', 'energyType', 'condition', 'equation', 'occurs'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const process = PROCESSES[randInt(0, 1)];
                    const facts = FACTS[process];
                    return {
                        category: type,
                        process: process,
                        correctText: facts[type],
                        badge: facts.label.toUpperCase(),
                        badgeVariant: process === 'photosynthesis' ? 'a' : 'b',
                        questionText: QUESTION_TEXT[type].replace('PROCESS', facts.label.toLowerCase()),
                    };
                },

                buildChoices: function(q) {
                    const otherAnswer = FACTS[otherProcess(q.process)][q.category];
                    const extras = EXTRA_DISTRACTORS[q.category];
                    return shuffle([q.correctText, otherAnswer].concat(extras));
                },

                hintFor: function(q) {
                    return HINTS[q.category];
                },

                explanationFor: function(q) {
                    return FACTS[q.process].label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered photosynthesis and respiration!",
            });
        })();
    </script>
@endpush
