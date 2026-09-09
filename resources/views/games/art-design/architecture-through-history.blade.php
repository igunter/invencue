@extends('layouts.app')

@section('meta_title', 'Architecture Through History — GCSE Art & Design Game')
@section('meta_blurb', 'A free GCSE Art & Design game covering architecture through history — famous buildings and styles like Gothic, Renaissance and Modernist.')
@section('meta_words', 'gcse architecture game, gothic renaissance modernist architecture quiz, famous buildings, gcse art and design')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-building',
        'title' => 'Architecture Through History',
        'subtitle' => 'Pick your question types, then test your knowledge!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'styles', 'label' => 'Architectural styles'],
            ['id' => 'buildings', 'label' => 'Famous buildings'],
        ],
        'aboutTitle' => 'About this architecture through history game',
        'aboutText' => 'This free GCSE Art & Design game covers architectural styles through history, from Gothic and Renaissance to Modernist and Postmodern, plus the famous buildings that show them off.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const STYLES = {
                'Gothic': 'A style from medieval Europe featuring pointed arches, ribbed vaults and tall stained-glass windows.',
                'Renaissance': 'A style reviving classical Greek and Roman ideas, using symmetry, columns and domes.',
                'Baroque': 'A dramatic, highly decorated style using curves, grand scale and elaborate ornament.',
                'Neoclassical': 'A style reviving simple, symmetrical classical Greek and Roman forms, popular in the 1700s-1800s.',
                'Modernist': 'A 20th-century style favouring simple geometric forms, minimal decoration and new materials like steel and glass.',
                'Art Deco': 'A 1920s-1930s style using bold geometric patterns, rich colours and streamlined shapes.',
                'Brutalist': 'A mid-20th-century style using raw, exposed concrete and blocky, massive forms.',
                'Romanesque': 'An early medieval style using thick walls, rounded arches and small windows.',
                'Victorian': 'A 19th-century British style often combining Gothic details with new industrial materials.',
                'Postmodern': 'A late-20th-century style that playfully mixes historical styles and bold, unconventional shapes.',
            };
            const STYLE_NAMES = Object.keys(STYLES);

            const BUILDINGS = {
                'Notre-Dame Cathedral, Paris': 'A Gothic cathedral famous for its flying buttresses and rose windows.',
                "St Peter's Basilica, Rome": 'A Renaissance and Baroque church with a dome designed with help from Michelangelo.',
                'The Houses of Parliament, London': 'A Victorian building built in the Gothic Revival style.',
                'The Sydney Opera House': 'A modernist building famous for its sail-like, curved concrete shells.',
                'The Eiffel Tower, Paris': 'An iron tower built in 1889, showing off new industrial engineering.',
                'The Chrysler Building, New York': 'A skyscraper famous for its Art Deco spire and decoration.',
                'The Colosseum, Rome': 'An ancient Roman amphitheatre built using arches and concrete.',
                'The Guggenheim Museum, Bilbao': 'A postmodern museum known for its swirling, titanium-clad curves.',
                'The Barbican Centre, London': 'A Brutalist arts complex built from raw, exposed concrete.',
                'Fallingwater': 'A modernist house designed by Frank Lloyd Wright, built dramatically over a waterfall.',
            };
            const BUILDING_NAMES = Object.keys(BUILDINGS);

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
                storageKey: 'architecture-through-historyGame.settings',
                types: ['styles', 'buildings'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'styles') {
                        const style = STYLE_NAMES[randInt(0, STYLE_NAMES.length - 1)];
                        return {
                            category: type,
                            label: style,
                            correctText: STYLES[style],
                            questionText: "What are the key features of " + style + " architecture?",
                        };
                    }
                    const building = BUILDING_NAMES[randInt(0, BUILDING_NAMES.length - 1)];
                    return {
                        category: type,
                        label: building,
                        correctText: BUILDINGS[building],
                        questionText: "What do you know about " + building + "?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'styles') {
                        const distractors = pickOthers(STYLE_NAMES, q.label, 3).map(function(t) { return STYLES[t]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(BUILDING_NAMES, q.label, 3).map(function(t) { return BUILDINGS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'styles') {
                        return 'Think about the shapes, materials and era associated with this style.';
                    }
                    return 'Think about which architectural style and period this building belongs to.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered architecture through history!",
            });
        })();
    </script>
@endpush
