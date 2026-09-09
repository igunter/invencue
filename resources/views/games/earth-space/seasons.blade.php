@extends('layouts.app')

@section('meta_title', 'Seasons — Kids Earth Science Game')
@section('meta_blurb', 'A free earth science game for young kids — match each season to its weather and clothes, and learn why we have seasons.')
@section('meta_words', 'seasons game, kids earth science game, summer winter spring autumn, why we have seasons for kids')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-calendar3',
        'title' => 'Seasons',
        'subtitle' => 'Pick your question types, then test your seasons knowledge!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'match', 'label' => 'Weather & clothes'],
            ['id' => 'facts', 'label' => 'Seasons facts'],
        ],
        'aboutTitle' => 'About this seasons game',
        'aboutText' => 'This free earth science game helps young kids match each season to its weather and clothes, and learn why we have seasons, what a year is, and what deciduous trees and hibernation are. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const MATCH = {
                'Summer': 'Warm or hot weather — you might wear shorts and t-shirts and go swimming.',
                'Winter': 'Cold weather, sometimes with snow — you might wear coats, hats and gloves.',
                'Spring': 'Mild weather with flowers blooming and baby animals being born.',
                'Autumn': 'Cooler weather with leaves turning orange and brown and falling from trees.',
            };
            const SEASON_NAMES = Object.keys(MATCH);

            const FACTS = {
                'Why we have seasons': 'The Earth is tilted, so different parts get more or less direct sunlight as it orbits the Sun.',
                'A year': 'The time it takes for the Earth to orbit the Sun once, containing all four seasons.',
                'Deciduous trees': 'Trees that lose their leaves in autumn and grow new ones in spring.',
                'Hibernation': 'When some animals sleep through the cold winter months to save energy.',
            };
            const FACT_NAMES = Object.keys(FACTS);

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
                storageKey: 'seasonsGame.settings',
                types: ['match', 'facts'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'match') {
                        const season = SEASON_NAMES[randInt(0, SEASON_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: MATCH[season],
                            questionText: 'What is ' + season.toLowerCase() + ' usually like?',
                        };
                    }
                    const term = FACT_NAMES[randInt(0, FACT_NAMES.length - 1)];
                    return {
                        category: type,
                        correctText: FACTS[term],
                        questionText: "What is '" + term + "'?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'match') {
                        const season = SEASON_NAMES.find(function(s) { return MATCH[s] === q.correctText; });
                        const distractors = pickOthers(SEASON_NAMES, season, 3).map(function(s) { return MATCH[s]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const term = FACT_NAMES.find(function(t) { return FACTS[t] === q.correctText; });
                    const distractors = pickOthers(FACT_NAMES, term, 3).map(function(t) { return FACTS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'match') {
                        return 'Think about hot or cold, and what you would wear outside.';
                    }
                    return 'Think about the Earth tilting, a full orbit, trees losing leaves, or animals sleeping all winter.';
                },

                explanationFor: function(q) {
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a seasons superstar!",
            });
        })();
    </script>
@endpush
