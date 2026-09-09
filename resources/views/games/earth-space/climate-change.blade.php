@extends('layouts.app')

@section('meta_title', 'Climate Change — GCSE Earth Science Game')
@section('meta_blurb', 'A free GCSE earth science game covering the greenhouse effect and the evidence for climate change.')
@section('meta_words', 'climate change game, greenhouse effect game, gcse earth science game, global warming, carbon footprint, ice core data')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-exclamation-triangle-fill',
        'title' => 'Climate Change',
        'subtitle' => 'Pick your question types, then test your climate knowledge!',
        'typeToggles' => [
            ['id' => 'greenhouse', 'label' => 'The greenhouse effect'],
            ['id' => 'evidence', 'label' => 'Evidence for climate change'],
        ],
        'aboutTitle' => 'About this climate change game',
        'aboutText' => 'This free GCSE earth science game covers how the greenhouse effect works and why it drives global warming, plus the different types of evidence scientists use to track climate change over time. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const GREENHOUSE = {
                'Greenhouse effect': 'The natural process where greenhouse gases trap heat from the Sun, keeping the Earth warm enough to support life.',
                'Carbon dioxide (CO₂)': 'A greenhouse gas released by burning fossil fuels and by deforestation; the main driver of recent climate change.',
                'Methane (CH₄)': 'A powerful greenhouse gas released by cattle farming, rice paddies and landfill sites.',
                'Global warming': "The long-term increase in the Earth's average temperature, mainly caused by rising greenhouse gas levels.",
                'Carbon footprint': 'A measure of the total greenhouse gases released by a person, product or activity.',
            };
            const GREENHOUSE_NAMES = Object.keys(GREENHOUSE);

            const EVIDENCE = {
                'Ice core data': 'Trapped air bubbles in ancient ice reveal past atmospheric CO₂ levels and temperatures.',
                'Rising sea levels': 'Caused by melting ice sheets and glaciers, and by seawater expanding as it warms.',
                'Temperature records': 'Direct measurements over the last 150+ years show a clear warming trend.',
                'Melting glaciers and ice sheets': 'Satellite and ground measurements show polar and glacier ice shrinking over recent decades.',
            };
            const EVIDENCE_NAMES = Object.keys(EVIDENCE);

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
                storageKey: 'climateChangeGame.settings',
                types: ['greenhouse', 'evidence'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const pool = type === 'greenhouse' ? GREENHOUSE : EVIDENCE;
                    const names = type === 'greenhouse' ? GREENHOUSE_NAMES : EVIDENCE_NAMES;
                    const key = names[randInt(0, names.length - 1)];
                    return {
                        category: type,
                        label: key,
                        correctText: pool[key],
                        questionText: "What does '" + key + "' mean?",
                    };
                },

                buildChoices: function(q) {
                    const pool = q.category === 'greenhouse' ? GREENHOUSE : EVIDENCE;
                    const names = q.category === 'greenhouse' ? GREENHOUSE_NAMES : EVIDENCE_NAMES;
                    const distractors = pickOthers(names, q.label, 3).map(function(k) { return pool[k]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'greenhouse') {
                        return 'Think about whether this is the process itself, a specific gas, the overall trend, or a way of measuring impact.';
                    }
                    return 'Think about what kind of measurement or record this is, and what it shows changing over time.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered climate change!",
            });
        })();
    </script>
@endpush
