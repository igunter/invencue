@extends('layouts.app')

@section('meta_title', 'Memory Models — GCSE Psychology Game')
@section('meta_blurb', 'A free GCSE psychology game covering the multi-store model of memory — encoding, capacity and duration.')
@section('meta_words', 'memory models game, multi store model, sensory short-term long-term memory, gcse psychology game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-clipboard-data',
        'title' => 'Memory Models',
        'subtitle' => 'Read the clue, then name the term!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Memory models'],
        ],
        'aboutTitle' => 'About this memory models game',
        'aboutText' => 'This free GCSE psychology game covers the multi-store model of memory — sensory, short-term and long-term memory — and key terms like encoding, capacity, duration and displacement.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const MODELS = {
                'Multi-store model': 'A model of memory with three separate stores: sensory, short-term and long-term memory.',
                'Sensory memory': 'Holds sensory information for a very brief time, often less than a second.',
                'Short-term memory (STM)': 'Has a limited capacity and duration, holding information for up to about 18-30 seconds.',
                'Long-term memory (LTM)': 'Has a potentially unlimited capacity and can store information for a lifetime.',
                'Encoding': 'The process of converting information into a form that can be stored in memory.',
                'Capacity': 'The amount of information that can be held in a memory store.',
                'Duration': 'How long information can be held in a memory store before it is lost.',
                'Rehearsal loop': 'Repeating information to keep it in short-term memory and help transfer it to long-term memory.',
                'Acoustic coding': 'Storing information based on its sound, typically used in short-term memory.',
                'Semantic coding': 'Storing information based on its meaning, typically used in long-term memory.',
                "Miller's magic number (7±2)": 'The idea that short-term memory can typically hold around 5 to 9 items.',
                'Displacement': 'Losing information from short-term memory because new information pushes it out.',
            };
            const NAMES = Object.keys(MODELS);

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
                storageKey: 'memoryModelsGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const name = NAMES[randInt(0, NAMES.length - 1)];
                    return {
                        category: type,
                        label: name,
                        correctText: name,
                        questionText: MODELS[name],
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(NAMES, q.label, 3);
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about capacity, duration, and how information is coded in each memory store.';
                },

                explanationFor: function(q) {
                    return q.correctText + ': ' + MODELS[q.correctText];
                },

                masteryMessage: "Amazing! You've mastered memory models!",
            });
        })();
    </script>
@endpush
