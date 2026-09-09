@extends('layouts.app')

@section('meta_title', 'Food Chains & Webs — Kids Biology Game')
@section('meta_blurb', 'A free biology game for kids — learn the roles in a food chain (producer, consumer, predator, prey, decomposer) and how food webs connect.')
@section('meta_words', 'food chains game, food webs game, kids biology game, producer consumer predator prey decomposer')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-diagram-3',
        'title' => 'Food Chains & Webs',
        'subtitle' => 'Pick your question types, then test your food chain knowledge!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'roles', 'label' => 'Roles in a food chain'],
            ['id' => 'chains', 'label' => 'How food chains work'],
        ],
        'aboutTitle' => 'About this food chains & webs game',
        'aboutText' => 'This free biology game covers the roles found in every food chain — producer, primary and secondary consumer, predator, prey and decomposer — plus how energy flows through a food chain and how food webs connect several chains together. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const ROLES = {
                'Producer': 'An organism that makes its own food using light energy, usually a green plant.',
                'Primary consumer': 'An animal that eats producers (plants) directly — a herbivore.',
                'Secondary consumer': 'An animal that eats primary consumers — a carnivore or omnivore.',
                'Decomposer': 'An organism that breaks down dead material, returning nutrients to the soil.',
                'Predator': 'An animal that hunts and eats other animals.',
                'Prey': 'An animal that is hunted and eaten by a predator.',
                'Tertiary consumer': 'An animal that eats secondary consumers — usually a top predator.',
                'Herbivore': 'An animal that only eats plants.',
                'Carnivore': 'An animal that only eats other animals.',
                'Omnivore': 'An animal that eats both plants and other animals.',
            };
            const ROLE_NAMES = Object.keys(ROLES);

            const CHAINS = {
                'An arrow in a food chain': 'Points in the direction that energy flows, from what is eaten to what eats it.',
                'Grass → Rabbit → Fox': 'Shows energy flowing from grass to rabbit to fox — the rabbit eats grass, and the fox eats the rabbit.',
                "Losing a food chain's producer": 'Affects every organism in the chain, since all of their energy traces back to that producer.',
                'A food web': 'Shows how several food chains overlap and connect within a habitat.',
                'Removing a predator from a food chain': 'Can cause the prey population it used to eat to grow much larger.',
                'The Sun': "The original source of energy for almost every food chain, captured by producers.",
                'Grass → Grasshopper → Frog → Snake': 'Shows energy passing along four trophic levels, from producer to top predator.',
                'A food chain with fewer links': 'Usually loses less energy overall, since energy is lost at every stage of the chain.',
                'Algae → Small fish → Big fish → Shark': 'Shows a marine food chain, with the shark as the top predator.',
                'Two overlapping food chains': 'Happens when the same organism appears in more than one food chain, forming a food web.',
            };
            const CHAIN_NAMES = Object.keys(CHAINS);

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
                storageKey: 'foodChainsWebsGame.settings',
                types: ['roles', 'chains'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const pool = type === 'roles' ? ROLES : CHAINS;
                    const names = type === 'roles' ? ROLE_NAMES : CHAIN_NAMES;
                    const key = names[randInt(0, names.length - 1)];
                    return {
                        category: type,
                        label: key,
                        correctText: pool[key],
                        questionText: type === 'roles' ? ("What is a '" + key + "'?") : ("What does '" + key + "' show?"),
                    };
                },

                buildChoices: function(q) {
                    const pool = q.category === 'roles' ? ROLES : CHAINS;
                    const names = q.category === 'roles' ? ROLE_NAMES : CHAIN_NAMES;
                    const distractors = pickOthers(names, q.label, 3).map(function(k) { return pool[k]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'roles') {
                        return 'Think about whether it makes food, eats plants, eats other animals, hunts, gets hunted, or breaks down dead things.';
                    }
                    return 'Think about the direction energy moves — always from what gets eaten towards what eats it.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You're a food chains superstar!",
            });
        })();
    </script>
@endpush
