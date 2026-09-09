@extends('layouts.app')

@section('meta_title', 'Moon Phases — Kids Earth Science Game')
@section('meta_blurb', 'A free earth science game for kids — put the phases of the Moon in order, learn what waxing, waning, new moon and full moon mean, and the traditional names for each full moon.')
@section('meta_words', 'moon phases game, kids earth science game, waxing waning crescent gibbous, new moon full moon, full moon names')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-circle',
        'title' => 'Moon Phases',
        'subtitle' => 'Pick your question types, then test your moon knowledge!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'order', 'label' => 'Phase order'],
            ['id' => 'facts', 'label' => 'Phase facts'],
            ['id' => 'names', 'label' => 'Full moon names'],
        ],
        'aboutTitle' => 'About this moon phases game',
        'aboutText' => 'This free earth science game covers the order of the 8 phases of the Moon (including what comes before, after and opposite each phase), what waxing, waning, new moon and full moon actually mean, how long a full cycle takes, and the traditional names given to each month\'s full moon. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const PHASES = [
                'New Moon', 'Waxing Crescent', 'First Quarter', 'Waxing Gibbous',
                'Full Moon', 'Waning Gibbous', 'Last Quarter', 'Waning Crescent',
            ];

            const FACTS = {
                'New Moon': "The Moon is between the Earth and Sun, so its lit side faces away from us — it looks invisible.",
                'First Quarter': "Half of the Moon's face is lit, appearing as a right-hand half circle.",
                'Full Moon': "The Moon's whole face is lit up and fully visible.",
                'Last Quarter': "Half of the Moon's face is lit, appearing as a left-hand half circle.",
                'Waxing': 'The lit part of the Moon is growing bigger each night.',
                'Waning': 'The lit part of the Moon is getting smaller each night.',
                'Waxing Crescent': "A thin, growing sliver of the Moon is lit, appearing just after the New Moon.",
                'Waxing Gibbous': "More than half of the Moon is lit and growing, appearing between the First Quarter and Full Moon.",
                'Waning Gibbous': "More than half of the Moon is lit and shrinking, appearing between the Full Moon and Last Quarter.",
                'Waning Crescent': "A thin, shrinking sliver of the Moon is lit, appearing just before the New Moon.",
                'The lunar cycle': "It takes about 29.5 days for the Moon to go through all 8 phases and return to New Moon again — this is called a lunar month.",
            };
            const FACT_NAMES = Object.keys(FACTS);

            const MOON_NAMES = {
                'January': 'Wolf Moon',
                'February': 'Snow Moon',
                'March': 'Worm Moon',
                'April': 'Pink Moon',
                'May': 'Flower Moon',
                'June': 'Strawberry Moon',
                'July': 'Buck Moon',
                'August': 'Sturgeon Moon',
                'September': 'Harvest Moon',
                'October': "Hunter's Moon",
                'November': 'Beaver Moon',
                'December': 'Cold Moon',
            };
            const MONTH_NAMES = Object.keys(MOON_NAMES);

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
                storageKey: 'moonPhasesGame.settings',
                types: ['order', 'facts', 'names'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'order') {
                        const index = randInt(0, PHASES.length - 1);
                        const kind = randInt(0, 2);
                        if (kind === 0) {
                            const nextIndex = (index + 1) % PHASES.length;
                            return {
                                category: type,
                                correctText: PHASES[nextIndex],
                                questionText: "Which moon phase comes after '" + PHASES[index] + "'?",
                            };
                        }
                        if (kind === 1) {
                            const prevIndex = (index + PHASES.length - 1) % PHASES.length;
                            return {
                                category: type,
                                correctText: PHASES[prevIndex],
                                questionText: "Which moon phase comes before '" + PHASES[index] + "'?",
                            };
                        }
                        const oppIndex = (index + PHASES.length / 2) % PHASES.length;
                        return {
                            category: type,
                            correctText: PHASES[oppIndex],
                            questionText: "Which moon phase is opposite '" + PHASES[index] + "' in the cycle?",
                        };
                    }
                    if (type === 'names') {
                        const month = MONTH_NAMES[randInt(0, MONTH_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: MOON_NAMES[month],
                            questionText: "What is the traditional name for the full moon in " + month + "?",
                        };
                    }
                    const term = FACT_NAMES[randInt(0, FACT_NAMES.length - 1)];
                    return {
                        category: type,
                        correctText: FACTS[term],
                        questionText: "What does '" + term + "' mean?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'order') {
                        const distractors = pickOthers(PHASES, q.correctText, 3);
                        return shuffle([q.correctText].concat(distractors));
                    }
                    if (q.category === 'names') {
                        const distractors = pickOthers(MONTH_NAMES.map(function(m) { return MOON_NAMES[m]; }), q.correctText, 3);
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const term = FACT_NAMES.find(function(t) { return FACTS[t] === q.correctText; });
                    const distractors = pickOthers(FACT_NAMES, term, 3).map(function(t) { return FACTS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'order') {
                        return 'The cycle goes: new, waxing crescent, first quarter, waxing gibbous, full, waning gibbous, last quarter, waning crescent — then new again. The phase opposite one is 4 steps away in that cycle.';
                    }
                    if (q.category === 'names') {
                        return 'These traditional names often come from nature — animals, weather or harvests linked to that time of year.';
                    }
                    return 'Think about whether the Moon looks invisible, half-lit, fully lit, growing, or shrinking.';
                },

                explanationFor: function(q) {
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a moon phases superstar!",
            });
        })();
    </script>
@endpush
