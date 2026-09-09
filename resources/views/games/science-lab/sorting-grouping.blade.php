@extends('layouts.app')

@section('meta_title', 'Sorting & Grouping — Kids Science Game')
@section('meta_blurb', 'A free science game for young kids — group objects by a property such as colour, size, shape or material.')
@section('meta_words', 'sorting and grouping game, kids science game, classifying objects, colour size shape material, working scientifically for kids')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-collection',
        'title' => 'Sorting & Grouping',
        'subtitle' => 'Pick your question types, then sort it out!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'byProperty', 'label' => 'Group by property'],
            ['id' => 'oddOneOut', 'label' => 'Odd one out'],
        ],
        'aboutTitle' => 'About this sorting & grouping game',
        'aboutText' => 'This free science game helps young kids practise grouping objects by a shared property — such as colour, size, shape or material — and spotting the odd one out in a group. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const OBJECTS = [
                { name: 'Apple', colour: 'Red', shape: 'Round', material: 'Living' },
                { name: 'Strawberry', colour: 'Red', shape: 'Round', material: 'Living' },
                { name: 'Tomato', colour: 'Red', shape: 'Round', material: 'Living' },
                { name: 'Banana', colour: 'Yellow', shape: 'Curved', material: 'Living' },
                { name: 'Lemon', colour: 'Yellow', shape: 'Oval', material: 'Living' },
                { name: 'Sun', colour: 'Yellow', shape: 'Round', material: 'Non-living' },
                { name: 'Grass', colour: 'Green', shape: 'Long', material: 'Living' },
                { name: 'Leaf', colour: 'Green', shape: 'Flat', material: 'Living' },
                { name: 'Frog', colour: 'Green', shape: 'Round', material: 'Living' },
                { name: 'Sky', colour: 'Blue', shape: 'Flat', material: 'Non-living' },
                { name: 'Football', colour: 'Blue', shape: 'Round', material: 'Non-living' },
                { name: 'Brick', colour: 'Red', shape: 'Rectangle', material: 'Non-living' },
                { name: 'Wooden block', colour: 'Brown', shape: 'Rectangle', material: 'Non-living' },
                { name: 'Coin', colour: 'Silver', shape: 'Round', material: 'Non-living' },
                { name: 'Book', colour: 'Blue', shape: 'Rectangle', material: 'Non-living' },
            ];

            const PROPERTIES = ['colour', 'shape', 'material'];
            const PROPERTY_LABELS = { colour: 'colour', shape: 'shape', material: 'type' };

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
                storageKey: 'sortingGroupingGame.settings',
                types: ['byProperty', 'oddOneOut'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'byProperty') {
                        const property = PROPERTIES[randInt(0, PROPERTIES.length - 1)];
                        const item = OBJECTS[randInt(0, OBJECTS.length - 1)];
                        return {
                            category: type,
                            property: property,
                            correctText: item[property],
                            questionText: 'What ' + PROPERTY_LABELS[property] + ' is a ' + item.name.toLowerCase() + '?',
                        };
                    }

                    const property = PROPERTIES[randInt(0, PROPERTIES.length - 1)];
                    const value = OBJECTS[randInt(0, OBJECTS.length - 1)][property];
                    const matching = shuffle(OBJECTS.filter(function(o) { return o[property] === value; }));
                    const oddOnes = shuffle(OBJECTS.filter(function(o) { return o[property] !== value; }));
                    if (matching.length < 3 || oddOnes.length < 1) {
                        return this.buildQuestion(type);
                    }
                    const group = matching.slice(0, 3).concat(oddOnes.slice(0, 1));
                    return {
                        category: type,
                        correctText: oddOnes[0].name,
                        options: shuffle(group).map(function(o) { return o.name; }),
                        questionText: 'Which one does not belong with the others?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'byProperty') {
                        const pool = OBJECTS.map(function(o) { return o[q.property]; });
                        const distractors = pickOthers(pool, q.correctText, 3);
                        return shuffle([q.correctText].concat(distractors));
                    }
                    return q.options;
                },

                hintFor: function(q) {
                    if (q.category === 'byProperty') {
                        return 'Look closely at what it looks like or what it is made of.';
                    }
                    return 'Three of these things share something in common — colour, shape or type. One does not.';
                },

                explanationFor: function(q) {
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a sorting & grouping superstar!",
            });
        })();
    </script>
@endpush
