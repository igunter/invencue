@extends('layouts.app')

@section('meta_title', 'Natural Hazards — GCSE Geography Game')
@section('meta_blurb', 'A free GCSE geography game covering natural hazards — earthquakes, volcanoes and tropical storms, their impacts and management.')
@section('meta_words', 'natural hazards game, gcse geography game, earthquakes volcanoes tropical storms, hazard management, gcse geography revision')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-exclamation-triangle',
        'title' => 'Natural Hazards',
        'subtitle' => 'Pick your question types, then test your natural hazards knowledge!',
        'typeToggles' => [
            ['id' => 'hazards', 'label' => 'Hazard types'],
            ['id' => 'management', 'label' => 'Impacts & management'],
        ],
        'aboutTitle' => 'About this natural hazards game',
        'aboutText' => 'This free GCSE geography game covers earthquakes, volcanoes and tropical storms as natural hazards, focusing on their impacts on people and how those impacts can be managed and reduced. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const HAZARDS = {
                'Natural hazard': 'A natural event that has the potential to threaten life, property or the environment.',
                'Tropical storm': 'A large, rotating storm with very strong winds and heavy rain that forms over warm tropical oceans.',
                'Earthquake': 'A sudden shaking of the ground caused by the release of built-up energy at a plate boundary.',
                'Volcanic eruption': 'The release of magma, ash and gases from below the Earth\'s surface through a volcano.',
                'Eye of the storm': 'The calm centre of a tropical storm, with low pressure and light winds.',
                'Storm surge': 'A rise in sea level caused by a tropical storm pushing water towards the coast, often causing flooding.',
                'Primary effect': 'The immediate impact of a hazard, caused directly by the hazard event itself.',
                'Secondary effect': 'An impact that happens as a knock-on result of the primary effects of a hazard, often some time later.',
                'Hazard risk': 'The probability that a natural hazard event will happen and cause harm to people or property.',
                'Magnitude': 'A measurement of the size or strength of a hazard event, such as an earthquake.',
            };
            const HAZARD_NAMES = Object.keys(HAZARDS);

            const MANAGEMENT = {
                'Prediction': 'Using scientific data and monitoring equipment to try to forecast when and where a hazard will strike.',
                'Planning': 'Preparing in advance for a hazard, such as making evacuation routes or land-use rules.',
                'Protection': 'Using structures or design measures, such as sea walls or earthquake-proof buildings, to reduce the impact of a hazard.',
                'Mitigation': 'Taking action in advance to reduce the severity of a hazard\'s impacts.',
                'Evacuation': 'Moving people away from an area at risk before or during a hazard event.',
                'Early warning system': 'A system that detects a hazard forming and alerts people in time for them to respond.',
                'Aid (disaster relief)': 'Money, supplies or support given to help an area recover after a hazard event.',
                'Resilience': 'The ability of a place or community to cope with and recover quickly from a hazard.',
                'Building regulations': 'Rules that require buildings in hazard-prone areas to be constructed to withstand certain hazards.',
                'Emergency services response': 'The immediate action taken by services such as fire, ambulance and rescue teams after a hazard strikes.',
            };
            const MANAGEMENT_NAMES = Object.keys(MANAGEMENT);

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

            function bankFor(type) {
                return type === 'hazards' ? HAZARDS : MANAGEMENT;
            }

            function namesFor(type) {
                return type === 'hazards' ? HAZARD_NAMES : MANAGEMENT_NAMES;
            }

            window.ScienceQuiz.run({
                storageKey: 'naturalHazardsGame.settings',
                types: ['hazards', 'management'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const names = namesFor(type);
                    const bank = bankFor(type);
                    const term = names[randInt(0, names.length - 1)];
                    return {
                        category: type,
                        label: term,
                        correctText: bank[term],
                        questionText: "What does '" + term + "' mean?",
                    };
                },

                buildChoices: function(q) {
                    const bank = bankFor(q.category);
                    const names = namesFor(q.category);
                    const distractors = pickOthers(names, q.label, 3).map(function(t) { return bank[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'hazards') {
                        return 'Think about whether this describes the hazard itself, or a direct effect it causes.';
                    }
                    return 'Think about whether this happens before a hazard strikes, or as part of the response after it strikes.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered natural hazards!",
            });
        })();
    </script>
@endpush
