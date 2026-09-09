@extends('layouts.app')

@section('meta_title', 'Magnets — Kids Physics Game')
@section('meta_blurb', 'A free physics game for young kids — guess which objects a magnet will pick up, and learn about attracting and repelling.')
@section('meta_words', 'magnets game, kids physics game, magnetic materials, attract repel, north south poles for kids')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-link-45deg',
        'title' => 'Magnets',
        'subtitle' => 'Pick your question types, then test your magnet knowledge!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'predict', 'label' => 'Magnetic or not?'],
            ['id' => 'facts', 'label' => 'Magnet facts'],
        ],
        'aboutTitle' => 'About this magnets game',
        'aboutText' => 'This free physics game helps young kids guess which everyday objects a magnet will pick up, and learn about magnetic poles, attracting and repelling. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const OBJECTS = {
                'Iron nail': 'Magnetic',
                'Steel paperclip': 'Magnetic',
                'A steel fridge door': 'Magnetic',
                'Wooden pencil': 'Not magnetic',
                'Plastic ruler': 'Not magnetic',
                'Glass cup': 'Not magnetic',
                'Rubber ball': 'Not magnetic',
                'Gold ring': 'Not magnetic',
            };
            const OBJECT_NAMES = Object.keys(OBJECTS);

            const FACTS = {
                'Magnet': 'An object that attracts, or pulls towards it, certain metals like iron and steel.',
                'Magnetic poles': 'The two ends of a magnet, called north and south, where the pull is strongest.',
                'Attract': 'When two magnets, or a magnet and a magnetic metal, pull towards each other.',
                'Repel': 'When two magnets push away from each other, which happens when two like poles face each other.',
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
                storageKey: 'magnetsGame.settings',
                types: ['predict', 'facts'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'predict') {
                        const object = OBJECT_NAMES[randInt(0, OBJECT_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: OBJECTS[object],
                            questionText: 'Will a magnet pick up a ' + object.toLowerCase() + '?',
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
                    if (q.category === 'predict') {
                        return shuffle(['Magnetic', 'Not magnetic']);
                    }
                    const term = FACT_NAMES.find(function(t) { return FACTS[t] === q.correctText; });
                    const distractors = pickOthers(FACT_NAMES, term, 3).map(function(t) { return FACTS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'predict') {
                        return 'Magnets only pick up iron and steel — not wood, plastic, glass, rubber or most other metals like gold.';
                    }
                    return 'Think about pulling metals, the strongest points on a magnet, pulling together, or pushing apart.';
                },

                explanationFor: function(q) {
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a magnets superstar!",
            });
        })();
    </script>
@endpush
