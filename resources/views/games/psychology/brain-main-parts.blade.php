@extends('layouts.app')

@section('meta_title', "The Brain's Main Parts — Psychology Game for Kids")
@section('meta_blurb', "A free psychology game covering the brain's main parts, like the cerebrum, cerebellum and brainstem.")
@section('meta_words', 'brain parts game, cerebrum cerebellum brainstem, how the brain works, psychology game for kids')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-diagram-3',
        'title' => "The Brain's Main Parts",
        'subtitle' => 'Read the clue, then name the brain part!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Brain parts'],
        ],
        'aboutTitle' => "About this brain's main parts game",
        'aboutText' => 'This free game introduces the main structures of the brain — including the cerebrum, cerebellum, brainstem and key brain areas — and the simple job each one does.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const PARTS = {
                'Cerebrum': 'The largest part of the brain, responsible for thinking, memory, and voluntary movement.',
                'Cerebellum': 'The part of the brain that controls balance, coordination and smooth movement.',
                'Brainstem': 'The part of the brain that controls automatic things like breathing and heart rate.',
                'Frontal lobe': 'The area at the front of the brain involved in decision-making and personality.',
                'Occipital lobe': 'The area at the back of the brain mainly responsible for processing what you see.',
                'Temporal lobe': 'The area of the brain mainly involved in processing sound and understanding language.',
                'Parietal lobe': 'The area of the brain that processes touch, taste and spatial awareness.',
                'Hippocampus': 'A brain structure important for forming new long-term memories.',
                'Amygdala': 'A brain structure involved in processing emotions, especially fear.',
                'Neuron': 'A nerve cell that sends and receives messages in the brain and body.',
                'Synapse': 'The tiny gap between two neurons where signals pass from one to the next.',
            };
            const PART_NAMES = Object.keys(PARTS);

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
                storageKey: 'brainMainPartsGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const part = PART_NAMES[randInt(0, PART_NAMES.length - 1)];
                    return {
                        category: type,
                        label: part,
                        correctText: part,
                        questionText: PARTS[part],
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(PART_NAMES, q.label, 3);
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about what job that part of the brain does.';
                },

                explanationFor: function(q) {
                    return q.correctText + ': ' + PARTS[q.correctText];
                },

                masteryMessage: "Amazing! You know your way around the brain!",
            });
        })();
    </script>
@endpush
