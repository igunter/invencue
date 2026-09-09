@extends('layouts.app')

@section('meta_title', 'Materials Sort — Kids Chemistry Game')
@section('meta_blurb', 'A free chemistry game for young kids — match everyday objects to the material they are made from, and learn what makes each material special.')
@section('meta_words', 'materials sort game, kids chemistry game, metal wood glass plastic fabric, learn materials')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-box-seam',
        'title' => 'Materials Sort',
        'subtitle' => 'Pick your question types, then sort those materials!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'materials', 'label' => 'Which material?'],
            ['id' => 'properties', 'label' => 'Material properties'],
        ],
        'aboutTitle' => 'About this materials sort game',
        'aboutText' => 'This free chemistry game helps young kids learn to match everyday objects to the material they are made from — metal, wood, glass, plastic or fabric — and what makes each material special. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const OBJECTS = {
                'Spoon': 'Metal',
                'Coin': 'Metal',
                'Chair leg': 'Wood',
                'Pencil': 'Wood',
                'Window': 'Glass',
                'Bottle': 'Glass',
                'Toy brick': 'Plastic',
                'Bucket': 'Plastic',
                'Jumper': 'Fabric',
                'Sock': 'Fabric',
            };
            const OBJECT_NAMES = Object.keys(OBJECTS);
            const MATERIAL_LIST = ['Metal', 'Wood', 'Glass', 'Plastic', 'Fabric'];

            const PROPERTIES = {
                'Metal': 'Usually hard and shiny, and a good conductor of heat and electricity.',
                'Wood': 'Comes from trees, can be carved, and usually floats on water.',
                'Glass': 'See-through and hard, but can shatter if dropped.',
                'Plastic': 'Light, can be moulded into different shapes, and does not rust.',
                'Fabric': 'Soft and bendy, made from woven or knitted threads.',
            };

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
                storageKey: 'materialsSortGame.settings',
                types: ['materials', 'properties'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'materials') {
                        const object = OBJECT_NAMES[randInt(0, OBJECT_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: OBJECTS[object],
                            questionText: 'What material is a ' + object.toLowerCase() + ' usually made from?',
                        };
                    }
                    const material = MATERIAL_LIST[randInt(0, MATERIAL_LIST.length - 1)];
                    return {
                        category: type,
                        label: material,
                        correctText: PROPERTIES[material],
                        questionText: 'What is special about ' + material.toLowerCase() + '?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'materials') {
                        const distractors = pickOthers(MATERIAL_LIST, q.correctText, 3);
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(MATERIAL_LIST, q.label, 3).map(function(m) { return PROPERTIES[m]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'materials') {
                        return 'Think about whether it is shiny and hard, comes from a tree, is see-through, is bendy, or is soft.';
                    }
                    return 'Think about whether it conducts electricity, floats, shatters, moulds into shapes, or is woven.';
                },

                explanationFor: function(q) {
                    if (q.category === 'properties') return q.label + ': ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a materials sorting superstar!",
            });
        })();
    </script>
@endpush
