@extends('layouts.app')

@section('meta_title', 'Body Systems — Kids Biology Game')
@section('meta_blurb', 'A free biology game for kids — match organs to the body system they belong to, and learn what each system does.')
@section('meta_words', 'body systems game, kids biology game, circulatory respiratory digestive nervous system, organs')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-link-45deg',
        'title' => 'Body Systems',
        'subtitle' => 'Pick your question types, then test your body systems knowledge!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'organs', 'label' => 'Which system?'],
            ['id' => 'systems', 'label' => 'What does it do?'],
        ],
        'aboutTitle' => 'About this body systems game',
        'aboutText' => 'This free biology game covers which organs belong to the circulatory, respiratory, digestive and nervous systems, and what each system actually does. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const ORGANS = {
                'Heart': 'Circulatory system',
                'Blood vessels': 'Circulatory system',
                'Lungs': 'Respiratory system',
                'Trachea (windpipe)': 'Respiratory system',
                'Stomach': 'Digestive system',
                'Intestines': 'Digestive system',
                'Brain': 'Nervous system',
                'Nerves': 'Nervous system',
                'Skull': 'Skeletal system',
                'Ribs': 'Skeletal system',
                'Biceps': 'Muscular system',
                'Diaphragm': 'Muscular system',
                'Kidneys': 'Excretory system',
                'Bladder': 'Excretory system',
                'White blood cells': 'Immune system',
                'Lymph nodes': 'Immune system',
                'Pancreas (hormones)': 'Endocrine system',
                'Thyroid gland': 'Endocrine system',
                'Ovaries': 'Reproductive system',
                'Testes': 'Reproductive system',
            };
            const ORGAN_NAMES = Object.keys(ORGANS);
            const SYSTEM_LIST = ['Circulatory system', 'Respiratory system', 'Digestive system', 'Nervous system', 'Skeletal system', 'Muscular system', 'Excretory system', 'Immune system', 'Endocrine system', 'Reproductive system'];

            const SYSTEMS = {
                'Circulatory system': 'Pumps blood around the body, carrying oxygen and nutrients to cells.',
                'Respiratory system': 'Takes in oxygen and removes carbon dioxide from the body.',
                'Digestive system': 'Breaks down food so the body can absorb nutrients.',
                'Nervous system': 'Carries messages between the brain and the rest of the body.',
                'Skeletal system': 'Supports the body, protects organs and lets it move using bones.',
                'Muscular system': 'Contracts and relaxes muscles to move the body and its organs.',
                'Excretory system': 'Removes waste products and excess water from the body.',
                'Immune system': 'Defends the body against germs, infections and disease.',
                'Endocrine system': 'Releases hormones that control processes like growth and metabolism.',
                'Reproductive system': 'Produces sex cells and allows organisms to have offspring.',
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
                storageKey: 'bodySystemsGame.settings',
                types: ['organs', 'systems'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'organs') {
                        const organ = ORGAN_NAMES[randInt(0, ORGAN_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: ORGANS[organ],
                            questionText: 'Which body system does the ' + organ.toLowerCase() + ' belong to?',
                        };
                    }
                    const system = SYSTEM_LIST[randInt(0, SYSTEM_LIST.length - 1)];
                    return {
                        category: type,
                        label: system,
                        correctText: SYSTEMS[system],
                        questionText: 'What does the ' + system.toLowerCase() + ' do?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'organs') {
                        const distractors = pickOthers(SYSTEM_LIST, q.correctText, 3);
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(SYSTEM_LIST, q.label, 3).map(function(s) { return SYSTEMS[s]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'organs') {
                        return 'Think about whether it moves blood, moves air, moves food, or sends messages.';
                    }
                    return 'Think about blood, air, food, or messages — which one matches this system\'s name?';
                },

                explanationFor: function(q) {
                    if (q.category === 'systems') return q.label + ': ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You're a body systems superstar!",
            });
        })();
    </script>
@endpush
