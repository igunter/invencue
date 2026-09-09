@extends('layouts.app')

@section('meta_title', 'Migration & Demography — GCSE Geography Game')
@section('meta_blurb', 'A free GCSE geography game covering migration and demography key terms — push/pull factors, population pyramids and more.')
@section('meta_words', 'migration and demography game, gcse geography game, push pull factors, population pyramid, migration key terms, gcse geography revision')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-arrow-left-right',
        'title' => 'Migration & Demography',
        'subtitle' => 'Read the clue, then work out the answer!',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Migration & demography'],
        ],
        'aboutTitle' => 'About this migration & demography game',
        'aboutText' => 'This free GCSE geography game covers key migration and demography vocabulary — push and pull factors, population pyramids, and how and why populations move and change.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Migration': 'The movement of people from one place to live in another, either permanently or for a long period.',
                'Push factor': 'A reason that encourages people to move away from where they currently live, such as conflict or a lack of jobs.',
                'Pull factor': 'A reason that attracts people to move to a new place, such as better job opportunities or safety.',
                'Immigration': 'The movement of people into a country to live there.',
                'Emigration': 'The movement of people out of a country to live elsewhere.',
                'Refugee': 'A person forced to leave their country, often due to conflict, persecution or disaster.',
                'Economic migrant': 'A person who moves to another place mainly to find work or improve their standard of living.',
                'Population pyramid': 'A graph showing the age and sex structure of a population, with age groups on the vertical axis.',
                'Dependent population': 'The parts of a population, usually the young and elderly, who rely on the working-age population for support.',
                'Working-age population': 'The proportion of a population, typically aged around 15 to 64, who are able to work.',
                'Life expectancy': 'The average number of years a person born in a particular place is expected to live.',
                'Ageing population': 'A population in which the proportion of older people is increasing, often due to rising life expectancy and falling birth rates.',
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
                storageKey: 'migrationDemographyGame.settings',
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
                    return 'Think about whether this term is about moving people, or about the make-up and structure of a population.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered migration and demography!",
            });
        })();
    </script>
@endpush
