@extends('layouts.app')

@section('meta_title', 'Moon Phases — Kids Earth Science Game')
@section('meta_blurb', 'A free earth science game for kids — put the phases of the Moon in order and learn what waxing, waning, new moon and full moon mean.')
@section('meta_words', 'moon phases game, kids earth science game, waxing waning crescent gibbous, new moon full moon')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-circle',
        'title' => 'Moon Phases',
        'subtitle' => 'Pick your question types, then test your moon knowledge!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'order', 'label' => "What's next?"],
            ['id' => 'facts', 'label' => 'Phase facts'],
        ],
        'aboutTitle' => 'About this moon phases game',
        'aboutText' => 'This free earth science game covers the order of the 8 phases of the Moon, and what waxing, waning, new moon and full moon actually mean. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
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
                storageKey: 'moonPhasesGame.settings',
                types: ['order', 'facts'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'order') {
                        const index = randInt(0, PHASES.length - 1);
                        const nextIndex = (index + 1) % PHASES.length;
                        return {
                            category: type,
                            correctText: PHASES[nextIndex],
                            questionText: "Which moon phase comes after '" + PHASES[index] + "'?",
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
                    const term = FACT_NAMES.find(function(t) { return FACTS[t] === q.correctText; });
                    const distractors = pickOthers(FACT_NAMES, term, 3).map(function(t) { return FACTS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'order') {
                        return 'The cycle goes: new, waxing crescent, first quarter, waxing gibbous, full, waning gibbous, last quarter, waning crescent — then new again.';
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
