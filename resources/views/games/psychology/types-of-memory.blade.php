@extends('layouts.app')

@section('meta_title', 'Types of Memory — Psychology Game for Kids')
@section('meta_blurb', 'A free psychology game covering short-term and long-term memory, and how memory works.')
@section('meta_words', 'types of memory game, short term long term memory, memory game for kids, psychology game for kids')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-clock-history',
        'title' => 'Types of Memory',
        'subtitle' => 'Pick your question types, then test your memory knowledge!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'terms', 'label' => 'Memory terms'],
            ['id' => 'scenarios', 'label' => 'Which type of memory?'],
        ],
        'aboutTitle' => 'About this types of memory game',
        'aboutText' => 'This free game introduces the main types of memory — short-term, long-term, sensory and working memory — and how ideas like rehearsal and chunking help us remember things.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Short-term memory': 'Memory that holds a small amount of information for a short time, like remembering a phone number briefly.',
                'Long-term memory': 'Memory that can store information for a long time, sometimes for your whole life.',
                'Sensory memory': 'A very brief memory of something you just saw, heard or felt, lasting less than a second or two.',
                'Working memory': 'The part of memory you use to hold and work with information in your mind right now.',
                'Encoding': 'The process of putting information into your memory.',
                'Storage': 'Keeping information in your memory over time.',
                'Retrieval': 'Getting information back out of your memory when you need it.',
                'Forgetting': 'Losing access to information that was once in your memory.',
                'Rehearsal': 'Repeating information over and over to help remember it.',
                'Chunking': 'Grouping bits of information together to make them easier to remember.',
            };
            const TERM_NAMES = Object.keys(TERMS);

            const SCENARIOS = {
                "Remembering a friend's phone number just long enough to type it in": 'Short-term memory',
                "Remembering your own name": 'Long-term memory',
                "Noticing a flash of light for a split second": 'Sensory memory',
                "Holding a maths problem in your head while solving it step by step": 'Working memory',
                "Remembering how to ride a bike you learned years ago": 'Long-term memory',
                "Repeating a shopping list in your head so you don't forget it": 'Rehearsal',
                "Remembering the sound of a word for a fraction of a second after it's spoken": 'Sensory memory',
                "Recalling your best friend's birthday, which you've known for years": 'Long-term memory',
                "Grouping a long number into smaller chunks, like a phone number": 'Chunking',
                "Trying hard to remember something and finally recalling it": 'Retrieval',
            };
            const SCENARIO_NAMES = Object.keys(SCENARIOS);

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
                storageKey: 'typesOfMemoryGame.settings',
                types: ['terms', 'scenarios'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'terms') {
                        const term = TERM_NAMES[randInt(0, TERM_NAMES.length - 1)];
                        return {
                            category: type,
                            label: term,
                            correctText: TERMS[term],
                            questionText: "What does '" + term + "' mean?",
                        };
                    }
                    const scenario = SCENARIO_NAMES[randInt(0, SCENARIO_NAMES.length - 1)];
                    return {
                        category: type,
                        label: scenario,
                        correctText: SCENARIOS[scenario],
                        questionText: scenario + ' — which type of memory is this?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'terms') {
                        const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = shuffle(
                        SCENARIO_NAMES.map(function(s) { return SCENARIOS[s]; }).filter(function(a) { return a !== q.correctText; })
                    ).slice(0, 3);
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'terms') {
                        return 'Think about how long the memory lasts and what it is used for.';
                    }
                    return 'Think about how long that piece of information needs to stay in mind.';
                },

                explanationFor: function(q) {
                    if (q.category === 'terms') return q.label + ': ' + q.correctText;
                    return q.label + ' is an example of ' + q.correctText + '.';
                },

                masteryMessage: "Amazing! You've mastered how memory works!",
            });
        })();
    </script>
@endpush
