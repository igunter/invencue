@extends('layouts.app')

@section('meta_title', 'Acids & Alkalis — Kids Chemistry Game')
@section('meta_blurb', 'A free chemistry game for kids — sort everyday substances into acids, alkalis and neutrals, and learn about the pH scale and indicators.')
@section('meta_words', 'acids and alkalis game, kids chemistry game, pH scale, indicator, litmus paper, neutralisation')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-droplet-half',
        'title' => 'Acids & Alkalis',
        'subtitle' => 'Pick your question types, then test your pH knowledge!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'substances', 'label' => 'Acid, alkali or neutral?'],
            ['id' => 'terms', 'label' => 'Key terms'],
        ],
        'aboutTitle' => 'About this acids & alkalis game',
        'aboutText' => 'This free chemistry game covers sorting everyday substances like lemon juice, soap and pure water into acids, alkalis and neutrals, plus the pH scale, indicators, litmus paper and neutralisation. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const SUBSTANCES = {
                'Lemon juice': 'Acid',
                'Vinegar': 'Acid',
                'Battery acid': 'Acid',
                'Soap': 'Alkali',
                'Toothpaste': 'Alkali',
                'Oven cleaner': 'Alkali',
                'Pure water': 'Neutral',
                'Salt solution': 'Neutral',
                'Sulfuric acid': 'Acid',
                'Orange juice': 'Acid',
                'Bicarbonate of soda solution': 'Alkali',
                'Sodium hydroxide solution': 'Alkali',
                'Sugar solution': 'Neutral',
            };
            const SUBSTANCE_NAMES = Object.keys(SUBSTANCES);
            const CATEGORY_LIST = ['Acid', 'Alkali', 'Neutral'];

            const TERMS = {
                'pH scale': 'A scale from 0 to 14 used to measure how acidic or alkaline a substance is.',
                'Indicator': 'A substance that changes colour to show whether something is acidic, neutral, or alkaline.',
                'Neutralisation': 'A reaction between an acid and an alkali that produces a neutral, or close to neutral, solution.',
                'Litmus paper': 'A type of indicator paper that turns red in acids and blue in alkalis.',
                'Universal indicator': 'An indicator that turns a range of colours to show exactly how acidic or alkaline a substance is.',
                'Strong acid': 'An acid, such as hydrochloric acid, that fully ionises in water, releasing lots of hydrogen ions.',
                'Weak acid': 'An acid, such as vinegar, that only partly ionises in water, releasing fewer hydrogen ions.',
                'Alkali': 'A soluble base that dissolves in water to produce hydroxide ions, with a pH above 7.',
                'Base': 'A substance that can neutralise an acid; an alkali is simply a base that dissolves in water.',
                'Salt (chemistry)': 'A compound formed when the hydrogen in an acid is replaced by a metal, often made during neutralisation.',
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
                storageKey: 'acidsAlkalisGame.settings',
                types: ['substances', 'terms'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'substances') {
                        const substance = SUBSTANCE_NAMES[randInt(0, SUBSTANCE_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: SUBSTANCES[substance],
                            questionText: 'Is ' + substance.toLowerCase() + ' an acid, an alkali, or neutral?',
                        };
                    }
                    const term = TERM_NAMES[randInt(0, TERM_NAMES.length - 1)];
                    return {
                        category: type,
                        correctText: TERMS[term],
                        questionText: "What is '" + term + "'?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'substances') {
                        return shuffle(CATEGORY_LIST.slice());
                    }
                    const term = TERM_NAMES.find(function(t) { return TERMS[t] === q.correctText; });
                    const distractors = pickOthers(TERM_NAMES, term, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'substances') {
                        return 'Think about whether it tastes sour, feels soapy/slippery, or is neither.';
                    }
                    return 'Think about a number scale, a colour-changing substance, mixing acid and alkali, or coloured test paper.';
                },

                explanationFor: function(q) {
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're an acids and alkalis superstar!",
            });
        })();
    </script>
@endpush
