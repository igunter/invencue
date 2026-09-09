@extends('layouts.app')

@section('meta_title', 'Human Physiology — GCSE Biology Game')
@section('meta_blurb', 'A free GCSE biology game covering enzymes, homeostasis, digestion and hormones.')
@section('meta_words', 'human physiology game, gcse biology game, enzymes, homeostasis, digestion, hormones, insulin, negative feedback')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-list-check',
        'title' => 'Human Physiology',
        'subtitle' => 'Pick your question types, then test your body knowledge!',
        'typeToggles' => [
            ['id' => 'terms', 'label' => 'Enzymes, homeostasis & digestion'],
            ['id' => 'hormones', 'label' => 'Hormones'],
        ],
        'aboutTitle' => 'About this human physiology game',
        'aboutText' => 'This free GCSE biology game covers enzymes, homeostasis, digestion and the hormones that keep the body in balance — including insulin, glucagon, adrenaline, ADH and thyroxine. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Enzyme': 'A biological catalyst that speeds up a reaction without being used up.',
                'Substrate': 'The molecule an enzyme acts on.',
                'Active site': 'The part of an enzyme where the substrate binds.',
                'Denatured': "When an enzyme's shape is permanently changed, usually by heat, stopping it working.",
                'Homeostasis': 'The regulation of internal conditions to maintain a stable internal environment.',
                'Negative feedback': 'A control mechanism where a change triggers a response that reverses the change.',
                'Villi': 'Finger-like projections in the small intestine that increase surface area for absorption.',
                'Bile': 'A substance made in the liver that emulsifies fats, making them easier to digest.',
                'Peristalsis': 'Wave-like muscle contractions that push food along the gut.',
                'Receptor': 'A cell or organ that detects a stimulus — a change in the environment.',
            };
            const TERM_NAMES = Object.keys(TERMS);

            const HORMONES = {
                'Insulin': 'Made in the pancreas; lowers blood glucose by causing cells to take in glucose.',
                'Glucagon': 'Made in the pancreas; raises blood glucose by breaking down glycogen into glucose.',
                'Adrenaline': "Made in the adrenal glands; prepares the body for 'fight or flight'.",
                'ADH': 'Made in the pituitary gland; controls how much water the kidneys reabsorb.',
                'Thyroxine': "Made in the thyroid gland; controls the body's metabolic rate.",
                'Oestrogen': 'Made in the ovaries; controls the menstrual cycle and female secondary sexual characteristics.',
                'Testosterone': 'Made in the testes; controls sperm production and male secondary sexual characteristics.',
                'FSH': 'Made in the pituitary gland; causes an egg to mature in the ovary.',
                'Growth hormone': 'Made in the pituitary gland; stimulates growth in bones and muscles.',
                'Progesterone': 'Made in the ovaries; maintains the lining of the uterus during the menstrual cycle and pregnancy.',
            };
            const HORMONE_NAMES = Object.keys(HORMONES);

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
                storageKey: 'humanPhysiologyGame.settings',
                types: ['terms', 'hormones'],
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
                    const hormone = HORMONE_NAMES[randInt(0, HORMONE_NAMES.length - 1)];
                    return {
                        category: type,
                        label: hormone,
                        correctText: HORMONES[hormone],
                        questionText: 'What does ' + hormone + ' do?',
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'terms') {
                        const term = TERM_NAMES.find(function(t) { return TERMS[t] === q.correctText; });
                        const distractors = pickOthers(TERM_NAMES, term, 3).map(function(t) { return TERMS[t]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(HORMONE_NAMES, q.label, 3).map(function(h) { return HORMONES[h]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'terms') {
                        return 'Think about which stage of digestion or which control system this word belongs to.';
                    }
                    return 'Think about which gland makes it, and whether its job is to raise something, lower something, or prepare the body for action.';
                },

                explanationFor: function(q) {
                    if (q.category === 'hormones') return q.label + ': ' + q.correctText;
                    return q.correctText;
                },

                masteryMessage: "Amazing! You've mastered human physiology!",
            });
        })();
    </script>
@endpush
