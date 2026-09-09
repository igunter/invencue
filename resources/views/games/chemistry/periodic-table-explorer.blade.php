@extends('layouts.app')

@section('meta_title', 'Periodic Table Explorer — Kids Chemistry Game')
@section('meta_blurb', 'A free chemistry game for kids — match elements to their symbols, and learn about periods, groups, metals and non-metals.')
@section('meta_words', 'periodic table game, kids chemistry game, element symbols, metals non-metals, groups and periods')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-table',
        'title' => 'Periodic Table Explorer',
        'subtitle' => 'Pick your question types, then test your element knowledge!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'symbols', 'label' => 'Element symbols'],
            ['id' => 'facts', 'label' => 'Periodic table facts'],
        ],
        'aboutTitle' => 'About this periodic table explorer game',
        'aboutText' => 'This free chemistry game covers matching common elements to their chemical symbols, plus what periods, groups, metals, non-metals and noble gases are. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const ELEMENTS = {
                'Hydrogen': 'H',
                'Oxygen': 'O',
                'Carbon': 'C',
                'Nitrogen': 'N',
                'Sodium': 'Na',
                'Chlorine': 'Cl',
                'Iron': 'Fe',
                'Gold': 'Au',
                'Helium': 'He',
                'Calcium': 'Ca',
            };
            const ELEMENT_NAMES = Object.keys(ELEMENTS);

            const FACTS = {
                'Period': 'A horizontal row in the periodic table.',
                'Group': 'A vertical column in the periodic table; elements in the same group have similar properties.',
                'Metal': 'Elements found on the left and middle of the periodic table; usually shiny, conduct electricity, and can be bent into shape.',
                'Non-metal': "Elements found on the right of the periodic table; usually dull and brittle, and don't conduct electricity well.",
                'Noble gases': 'Group 0 elements that are very unreactive, such as helium and neon.',
                'Alkali metals': 'Group 1 elements, such as lithium and sodium, that are soft, very reactive metals.',
                'Halogens': 'Group 7 elements, such as chlorine and iodine, that are reactive non-metals.',
                'Transition metals': 'A block of metals in the middle of the periodic table, such as iron and copper, often used to make useful alloys.',
                'Atomic number': "The number of protons in an atom's nucleus, which decides its position in the periodic table.",
                'Dmitri Mendeleev': 'The scientist often credited with arranging the first widely used periodic table, ordering elements by atomic mass and leaving gaps for undiscovered elements.',
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
                storageKey: 'periodicTableExplorerGame.settings',
                types: ['symbols', 'facts'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'symbols') {
                        const element = ELEMENT_NAMES[randInt(0, ELEMENT_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: ELEMENTS[element],
                            questionText: 'What is the chemical symbol for ' + element.toLowerCase() + '?',
                        };
                    }
                    const term = FACT_NAMES[randInt(0, FACT_NAMES.length - 1)];
                    return {
                        category: type,
                        correctText: FACTS[term],
                        questionText: "What is a '" + term + "'?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'symbols') {
                        const element = ELEMENT_NAMES.find(function(e) { return ELEMENTS[e] === q.correctText; });
                        const distractors = pickOthers(ELEMENT_NAMES, element, 3).map(function(e) { return ELEMENTS[e]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const term = FACT_NAMES.find(function(t) { return FACTS[t] === q.correctText; });
                    const distractors = pickOthers(FACT_NAMES, term, 3).map(function(t) { return FACTS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'symbols') {
                        return 'Most symbols start with the same letter as the element name — but not always!';
                    }
                    return 'Think about rows, columns, shiny reactive elements, dull elements, or the very unreactive ones.';
                },

                explanationFor: function(q) {
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a periodic table superstar!",
            });
        })();
    </script>
@endpush
