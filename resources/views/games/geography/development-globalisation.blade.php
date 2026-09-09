@extends('layouts.app')

@section('meta_title', 'Development & Globalisation — GCSE Geography Game')
@section('meta_blurb', 'A free GCSE geography game covering development and globalisation key terms — HICs, LICs, NEEs, TNCs and trade.')
@section('meta_words', 'development and globalisation game, gcse geography game, hic lic nee, tnc globalisation, gcse geography revision')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-graph-up-arrow',
        'title' => 'Development & Globalisation',
        'subtitle' => 'Read the clue, then work out the answer!',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Development & globalisation'],
        ],
        'aboutTitle' => 'About this development & globalisation game',
        'aboutText' => 'This free GCSE geography game covers key development and globalisation vocabulary — HICs, LICs and NEEs, trade, TNCs, and how the world\'s countries and economies have become more connected.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'HIC': 'Short for High Income Country — a country with a high level of income and development.',
                'LIC': 'Short for Low Income Country — a country with a low level of income and development.',
                'NEE': 'Short for Newly Emerging Economy — a country whose economy is developing and industrialising rapidly.',
                'Globalisation': 'The growing connection between countries through trade, culture, communication and movement of people.',
                'TNC': 'Short for Transnational Corporation — a company that operates and has offices or factories in more than one country.',
                'Trade': 'The exchange of goods and services between countries or people.',
                'Fair trade': 'A trading approach that aims to give producers in poorer countries a fairer price for their goods.',
                'Development gap': 'The difference in levels of development between the richest and poorest countries or regions.',
                'Quality of life': 'How good or bad the conditions of everyday life are for a person, including things money can\'t always buy.',
                'Standard of living': 'A measure of wealth and material comfort, often based on income and access to goods and services.',
                'Aid': 'Money, goods or help given by one country, organisation or person to support another.',
                'Outsourcing': 'When a company moves part of its production or services to be carried out in another country.',
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
                storageKey: 'developmentGlobalisationGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const term = TERM_NAMES[randInt(0, TERM_NAMES.length - 1)];
                    return {
                        category: type,
                        label: term,
                        correctText: TERMS[term],
                        questionText: "What does '" + term + "' mean?",
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about whether this term describes a type of country, a type of company, or a way people or places are connected.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered development and globalisation!",
            });
        })();
    </script>
@endpush
