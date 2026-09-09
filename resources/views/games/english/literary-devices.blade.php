@extends('layouts.app')

@section('meta_title', 'Literary Devices — GCSE English Game')
@section('meta_blurb', 'A free GCSE English game covering literary devices — alliteration, personification, onomatopoeia, irony and more.')
@section('meta_words', 'literary devices game, gcse english language game, alliteration personification onomatopoeia, foreshadowing irony, analysing texts')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-chat-quote',
        'title' => 'Literary Devices',
        'subtitle' => 'Pick your question types, then spot the device!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'terms', 'label' => 'Key terms'],
            ['id' => 'examples', 'label' => 'Spot the device'],
        ],
        'aboutTitle' => 'About this literary devices game',
        'aboutText' => 'This free GCSE English game covers common literary devices used by writers — alliteration, personification, onomatopoeia, metaphor, simile, hyperbole, foreshadowing, irony, symbolism and pathetic fallacy. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Alliteration': 'Repetition of the same consonant sound at the start of nearby words.',
                'Personification': 'Giving human qualities to an object, animal or idea.',
                'Onomatopoeia': 'A word that imitates the sound it describes.',
                'Metaphor': 'Describing something as if it actually is something else, without using "like" or "as".',
                'Simile': 'Comparing two things using "like" or "as".',
                'Hyperbole': 'Deliberate exaggeration for effect.',
                'Foreshadowing': 'A hint or clue about events that will happen later in the story.',
                'Irony': 'When the opposite of what is expected happens, or words mean the opposite of what they say.',
                'Symbolism': 'Using an object or image to represent a deeper idea.',
                'Pathetic fallacy': 'Giving the weather or nature human emotions to reflect the mood of a scene.',
            };
            const TERM_NAMES = Object.keys(TERMS);

            const EXAMPLES = {
                'The wind whispered through the trees.': 'Personification',
                'Peter Piper picked a peck of pickled peppers.': 'Alliteration',
                'The bacon sizzled in the pan.': 'Onomatopoeia',
                "I've told you a million times to tidy your room.": 'Hyperbole',
                'The classroom was a zoo.': 'Metaphor',
                'Her eyes sparkled like diamonds.': 'Simile',
                'Dark clouds gathered as the argument began.': 'Pathetic fallacy',
                'A fire alarm is mentioned early on, moments before the real fire breaks out.': 'Foreshadowing',
                'The fire station burned down.': 'Irony',
                'The white dove in the story represents peace.': 'Symbolism',
            };
            const EXAMPLE_NAMES = Object.keys(EXAMPLES);

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

            const DEVICE_NAMES = Array.from(new Set(TERM_NAMES.concat(Object.values(EXAMPLES))));

            window.ScienceQuiz.run({
                storageKey: 'literary-devicesGame.settings',
                types: ['terms', 'examples'],
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
                    const example = EXAMPLE_NAMES[randInt(0, EXAMPLE_NAMES.length - 1)];
                    return {
                        category: type,
                        correctText: EXAMPLES[example],
                        questionText: "'" + example + "' — which literary device is this?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'terms') {
                        const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(DEVICE_NAMES, q.correctText, 3);
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'terms') return 'Think about what effect this device has on sound, meaning or imagery.';
                    return 'Look for repeated sounds, exaggeration, human qualities given to objects, or comparisons.';
                },

                explanationFor: function(q) {
                    if (q.category === 'terms') return q.label + ': ' + q.correctText;
                    return 'This is an example of ' + q.correctText.toLowerCase() + '.';
                },

                masteryMessage: "Amazing! You've mastered literary devices!",
            });
        })();
    </script>
@endpush
