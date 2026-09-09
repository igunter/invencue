@extends('layouts.app')

@section('meta_title', 'Population & Urbanisation — GCSE Geography Game')
@section('meta_blurb', 'A free GCSE geography game covering population and urbanisation key terms — population density, urbanisation, megacities and more.')
@section('meta_words', 'population and urbanisation game, gcse geography game, urbanisation key terms, population density, megacity, gcse geography revision')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-people',
        'title' => 'Population & Urbanisation',
        'subtitle' => 'Read the clue, then work out the answer!',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Population & urbanisation'],
        ],
        'aboutTitle' => 'About this population & urbanisation game',
        'aboutText' => 'This free GCSE geography game covers key population and urbanisation vocabulary — population density, urbanisation, megacities, and the trends behind how and where the world\'s population is growing and moving.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Population density': 'The number of people living per unit of area, usually per square kilometre.',
                'Urbanisation': 'The growing proportion of people living in towns and cities rather than rural areas.',
                'Birth rate': 'The number of live births per 1,000 people in a population per year.',
                'Death rate': 'The number of deaths per 1,000 people in a population per year.',
                'Natural increase': 'Population growth calculated as the birth rate minus the death rate.',
                'Megacity': 'A city with a population of over 10 million people.',
                'Rural-urban migration': 'The movement of people from the countryside into towns and cities.',
                'Counter-urbanisation': 'The movement of people away from cities into surrounding rural areas.',
                'Suburbanisation': 'The growth of residential areas on the outskirts of a city.',
                'Population growth rate': 'The rate at which the number of people in a population increases over time.',
                'Push factor': 'A reason that encourages people to move away from where they currently live.',
                'Pull factor': 'A reason that attracts people to move to a new place.',
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
                storageKey: 'populationUrbanisationGame.settings',
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
                    return 'Think about whether this term describes a number, a movement of people, or a type of place.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered population and urbanisation!",
            });
        })();
    </script>
@endpush
