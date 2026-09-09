@extends('layouts.app')

@section('meta_title', 'Evolution & Natural Selection — GCSE Biology Game')
@section('meta_blurb', 'A free GCSE biology game covering natural selection, variation, speciation, extinction and real-world evidence for evolution.')
@section('meta_words', 'evolution game, natural selection game, gcse biology game, speciation, extinction, peppered moth, antibiotic resistance')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-clock-history',
        'title' => 'Evolution & Natural Selection',
        'subtitle' => 'Pick your question types, then test your evolution knowledge!',
        'typeToggles' => [
            ['id' => 'terms', 'label' => 'Key terms'],
            ['id' => 'examples', 'label' => 'Real-world examples'],
        ],
        'aboutTitle' => 'About this evolution & natural selection game',
        'aboutText' => 'This free GCSE biology game covers the vocabulary of evolution — variation, natural selection, adaptation, speciation and extinction — plus classic real-world examples like peppered moths, antibiotic-resistant bacteria and Darwin\'s finches. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Variation': 'Differences between individuals of the same species.',
                'Natural selection': 'The process where organisms best suited to their environment are more likely to survive and reproduce.',
                'Adaptation': 'A feature that helps an organism survive in its environment.',
                'Speciation': 'The formation of a new species, often when populations become too different to interbreed.',
                'Extinction': 'When every member of a species has died out.',
                'Fossil': 'The preserved remains or traces of an organism from long ago.',
                'Selective breeding': 'Choosing parents with desired characteristics to breed together, producing offspring with those characteristics.',
                'Antibiotic resistance': 'When bacteria evolve so that an antibiotic no longer kills them.',
                'Evolution': 'The gradual change in the inherited characteristics of a population over many generations.',
            };
            const TERM_NAMES = Object.keys(TERMS);

            const EXAMPLES = {
                'Peppered moths': 'Natural selection in action — darker moths became more common once pollution darkened tree bark and camouflaged them from predators.',
                'Antibiotic-resistant bacteria': 'Natural selection happening quickly — bacteria reproduce fast, so resistant ones survive treatment and multiply.',
                "Darwin's finches": 'Speciation — finches on different islands evolved different beak shapes suited to different food sources.',
                'The fossil record': 'Evidence for evolution — showing how organisms have changed gradually over millions of years.',
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

            window.ScienceQuiz.run({
                storageKey: 'evolutionNaturalSelectionGame.settings',
                types: ['terms', 'examples'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'terms') {
                        const term = TERM_NAMES[randInt(0, TERM_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: TERMS[term],
                            questionText: "What does '" + term + "' mean?",
                        };
                    }
                    const example = EXAMPLE_NAMES[randInt(0, EXAMPLE_NAMES.length - 1)];
                    return {
                        category: type,
                        label: example,
                        correctText: EXAMPLES[example],
                        questionText: "What is '" + example + "' an example of?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'terms') {
                        const term = TERM_NAMES.find(function(t) { return TERMS[t] === q.correctText; });
                        const distractors = pickOthers(TERM_NAMES, term, 3).map(function(t) { return TERMS[t]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(EXAMPLE_NAMES, q.label, 3).map(function(e) { return EXAMPLES[e]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'terms') {
                        return 'Think about whether this describes a difference between individuals, a process, or an outcome like a new or lost species.';
                    }
                    return 'Think about what actually changed in the population, and over what timescale it happened.';
                },

                explanationFor: function(q) {
                    if (q.category === 'examples') return q.label + ': ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You've mastered evolution and natural selection!",
            });
        })();
    </script>
@endpush
