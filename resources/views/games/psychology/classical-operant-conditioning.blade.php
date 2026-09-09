@extends('layouts.app')

@section('meta_title', 'Classical & Operant Conditioning — GCSE Psychology Game')
@section('meta_blurb', 'A free GCSE psychology game covering classical and operant conditioning, reinforcement and punishment.')
@section('meta_words', 'classical conditioning game, operant conditioning game, pavlov skinner, reinforcement punishment, gcse psychology game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-bell',
        'title' => 'Classical & Operant Conditioning',
        'subtitle' => 'Pick your question types, then test your knowledge!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'terms', 'label' => 'Key terms'],
            ['id' => 'examples', 'label' => 'Spot the concept'],
        ],
        'aboutTitle' => 'About this classical & operant conditioning game',
        'aboutText' => 'This free GCSE psychology game covers classical conditioning (learning through association, as shown by Pavlov) and operant conditioning (learning through consequences, as shown by Skinner), including key terms like reinforcement, punishment and extinction.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Classical conditioning': 'Learning through association between a neutral stimulus and an unconditioned stimulus.',
                'Operant conditioning': 'Learning through the consequences of behaviour, such as reinforcement or punishment.',
                'Unconditioned stimulus': 'Something that naturally triggers a response without any learning, like food causing salivation.',
                'Unconditioned response': 'A natural, unlearned response to an unconditioned stimulus, like salivating at food.',
                'Conditioned stimulus': 'A previously neutral stimulus that triggers a response after being paired with an unconditioned stimulus.',
                'Conditioned response': 'A learned response to a stimulus that was previously neutral.',
                'Positive reinforcement': 'Adding a pleasant reward to increase the chance a behaviour is repeated.',
                'Negative reinforcement': 'Removing something unpleasant to increase the chance a behaviour is repeated.',
                'Positive punishment': 'Adding something unpleasant to decrease the chance a behaviour is repeated.',
                'Negative punishment': 'Removing something pleasant to decrease the chance a behaviour is repeated.',
                'Extinction': 'The gradual disappearance of a learned response when it is no longer reinforced.',
            };
            const TERM_NAMES = Object.keys(TERMS);

            const EXAMPLES = {
                "Pavlov's dogs began salivating at the sound of a bell after it was repeatedly paired with food": 'Classical conditioning',
                "A rat presses a lever and receives a food pellet, so it presses the lever more often": 'Positive reinforcement',
                "A child tidies their room to stop a parent nagging them, so the tidying increases": 'Negative reinforcement',
                "A student loses screen time privileges for not doing homework, so the behaviour decreases": 'Negative punishment',
                "A dog is scolded for jumping up, so it jumps up less often": 'Positive punishment',
                "A child feels sick after eating a food that made them ill once, and now avoids it": 'Classical conditioning',
                "A trained behaviour stops happening once rewards are no longer given": 'Extinction',
                "An employee works harder after receiving a bonus for good performance": 'Positive reinforcement',
                "Skinner used a box to study how rewards and punishments shape animal behaviour": 'Operant conditioning',
                "A student feels anxious hearing a certain song because it played during a stressful exam": 'Classical conditioning',
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
                storageKey: 'classicalOperantConditioningGame.settings',
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
                        label: example,
                        correctText: EXAMPLES[example],
                        questionText: example + ' — which concept is this an example of?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'terms') {
                        const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = shuffle(
                        EXAMPLE_NAMES.map(function(e) { return EXAMPLES[e]; }).filter(function(a) { return a !== q.correctText; })
                    ).slice(0, 3);
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'terms') {
                        return 'Think about whether this is about pairing stimuli together, or about consequences following behaviour.';
                    }
                    return 'Think about whether learning happens through association, or through rewards and punishments.';
                },

                explanationFor: function(q) {
                    if (q.category === 'terms') return q.label + ': ' + q.correctText;
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You've mastered classical and operant conditioning!",
            });
        })();
    </script>
@endpush
