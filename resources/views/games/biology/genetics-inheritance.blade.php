@extends('layouts.app')

@section('meta_title', 'Genetics & Inheritance — GCSE Biology Game')
@section('meta_blurb', 'A free GCSE biology game covering genetics key terms and monohybrid genetic crosses — alleles, genotype, phenotype and Punnett square ratios.')
@section('meta_words', 'genetics game, inheritance game, gcse biology game, punnett square, alleles, genotype, phenotype, monohybrid cross')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-braces-asterisk',
        'title' => 'Genetics & Inheritance',
        'subtitle' => 'Pick your question types, then test your genetics knowledge!',
        'typeToggles' => [
            ['id' => 'terms', 'label' => 'Key terms'],
            ['id' => 'crosses', 'label' => 'Genetic crosses'],
        ],
        'aboutTitle' => 'About this genetics & inheritance game',
        'aboutText' => 'This free GCSE biology game covers the vocabulary and Punnett square logic behind genetics — alleles, genotype, phenotype, dominant and recessive traits, and the genotype ratios produced by classic monohybrid crosses. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Allele': 'A different version of the same gene.',
                'Gene': 'A section of DNA that codes for a particular characteristic.',
                'Genotype': "The genetic makeup of an organism — its combination of alleles.",
                'Phenotype': 'The observable characteristics of an organism.',
                'Dominant allele': 'An allele whose characteristic appears even if only one copy is present.',
                'Recessive allele': 'An allele whose characteristic only appears if two copies are present.',
                'Homozygous': 'Having two identical alleles for a gene.',
                'Heterozygous': 'Having two different alleles for a gene.',
                'Mutation': 'A random change in the base sequence of DNA.',
                'Variation': 'Differences between individuals of the same species.',
            };
            const TERM_NAMES = Object.keys(TERMS);

            const CROSSES = {
                'BB x bb': '100% of offspring are Bb (heterozygous)',
                'Bb x Bb': '1 BB : 2 Bb : 1 bb',
                'Bb x bb': '1 Bb : 1 bb',
                'BB x Bb': '1 BB : 1 Bb',
            };
            const CROSS_NAMES = Object.keys(CROSSES);

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
                const others = shuffle(pool.filter(function(x) { return x !== exclude; }));
                return others.slice(0, count);
            }

            window.ScienceQuiz.run({
                storageKey: 'geneticsInheritanceGame.settings',
                types: ['terms', 'crosses'],
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
                    const cross = CROSS_NAMES[randInt(0, CROSS_NAMES.length - 1)];
                    return {
                        category: type,
                        label: cross,
                        correctText: CROSSES[cross],
                        questionText: 'In a cross between ' + cross + ', what genotype ratio would you expect in the offspring?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'terms') {
                        const term = TERM_NAMES.find(function(t) { return TERMS[t] === q.correctText; });
                        const distractors = pickOthers(TERM_NAMES, term, 3).map(function(t) { return TERMS[t]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(CROSS_NAMES, q.label, 3).map(function(c) { return CROSSES[c]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'terms') {
                        return 'Think about whether this term describes DNA and genes themselves, or how those genes are inherited and expressed.';
                    }
                    return "Write a quick Punnett square: one parent's alleles across the top, the other's down the side, then fill in each combination.";
                },

                explanationFor: function(q) {
                    if (q.category === 'crosses') return q.label + ': ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You've mastered genetics and inheritance!",
            });
        })();
    </script>
@endpush
