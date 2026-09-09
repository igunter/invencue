@extends('layouts.app')

@section('meta_title', 'Bonding — GCSE Chemistry Game')
@section('meta_blurb', 'A free GCSE chemistry game comparing ionic and covalent bonding — formation, electron movement, examples and properties.')
@section('meta_words', 'bonding game, ionic bonding, covalent bonding, gcse chemistry game, chemical bonding revision')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-link-45deg',
        'title' => 'Bonding',
        'subtitle' => 'Pick your question types, then tell ionic and covalent bonding apart!',
        'typeToggles' => [
            ['id' => 'formation', 'label' => 'How the bond forms'],
            ['id' => 'electronMovement', 'label' => 'Electron movement'],
            ['id' => 'examples', 'label' => 'Examples'],
            ['id' => 'properties', 'label' => 'Properties'],
        ],
        'aboutTitle' => 'About this bonding game',
        'aboutText' => 'This free GCSE chemistry game tests the classic mix-up between ionic and covalent bonding — how each forms, what happens to electrons, typical examples, and the properties of substances they produce. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const BONDS = ['ionic', 'covalent', 'metallic'];

            const FACTS = {
                ionic: {
                    label: 'Ionic bonding',
                    phrase: 'an ionic bond',
                    formation: 'Formed by the transfer of electrons between a metal and a non-metal',
                    electronMovement: 'Electrons are transferred from one atom to another',
                    examples: 'Sodium chloride (NaCl) or magnesium oxide (MgO)',
                    properties: 'High melting point; conducts electricity when molten or dissolved in water',
                },
                covalent: {
                    label: 'Covalent bonding',
                    phrase: 'a covalent bond',
                    formation: 'Formed by the sharing of electrons between two non-metal atoms',
                    electronMovement: 'Electrons are shared between atoms',
                    examples: 'Water (H₂O) or carbon dioxide (CO₂)',
                    properties: 'Usually a low melting point; does not conduct electricity',
                },
                metallic: {
                    label: 'Metallic bonding',
                    phrase: 'a metallic bond',
                    formation: 'Formed between positive metal ions and a "sea" of delocalised electrons',
                    electronMovement: 'Electrons become delocalised and are free to move throughout the whole structure',
                    examples: 'Iron (Fe) or copper (Cu) metal',
                    properties: 'Good conductor of electricity and heat; malleable and can be bent into shape',
                },
            };

            const EXTRA_DISTRACTORS = {
                formation: ['Formed by the loss of protons between two metals', 'Formed only when atoms collide at very high temperature'],
                electronMovement: ['Electrons are destroyed during bonding', 'Electrons stay fixed in place and never move at all'],
                examples: ['Helium (He) gas', 'Diamond and graphite only'],
                properties: ['Always a gas at room temperature', 'Always magnetic'],
            };

            const QUESTION_TEXT = {
                formation: 'How is BONDTYPE formed?',
                electronMovement: 'What happens to electrons when BONDTYPE forms?',
                examples: 'Which of these is a typical example of a compound or metal held together by BONDTYPE?',
                properties: 'Which property is typical of a substance held together by BONDTYPE?',
            };

            const HINTS = {
                formation: 'Ionic bonding involves atoms swapping electrons completely, covalent involves atoms sharing them, and metallic involves electrons roaming freely among metal ions.',
                electronMovement: "'Transfer' means electrons move fully from one atom to another. 'Share' means both atoms keep some claim on them. 'Delocalised' means they belong to the whole structure.",
                examples: 'Ionic compounds are usually a metal joined to a non-metal. Covalent compounds are usually two or more non-metals. Metallic bonding is found in pure metals and alloys.',
                properties: 'Ionic compounds form solid lattices with strong forces between charged ions; covalent compounds are often molecular; metals are held together by free-moving electrons.',
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

            function otherBond(bond) {
                const others = BONDS.filter(function(b) { return b !== bond; });
                return others[randInt(0, others.length - 1)];
            }

            function badgeVariantFor(bond) {
                if (bond === 'ionic') return 'a';
                if (bond === 'covalent') return 'b';
                return 'c';
            }

            window.ScienceQuiz.run({
                storageKey: 'bondingGame.settings',
                types: ['formation', 'electronMovement', 'examples', 'properties'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const bond = BONDS[randInt(0, BONDS.length - 1)];
                    const facts = FACTS[bond];
                    return {
                        category: type,
                        bond: bond,
                        correctText: facts[type],
                        badge: facts.label.toUpperCase(),
                        badgeVariant: badgeVariantFor(bond),
                        questionText: QUESTION_TEXT[type].replace('BONDTYPE', facts.phrase),
                    };
                },

                buildChoices: function(q) {
                    const otherAnswer = FACTS[otherBond(q.bond)][q.category];
                    const extras = EXTRA_DISTRACTORS[q.category];
                    return shuffle([q.correctText, otherAnswer].concat(extras));
                },

                hintFor: function(q) {
                    return HINTS[q.category];
                },

                explanationFor: function(q) {
                    return FACTS[q.bond].label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered ionic and covalent bonding!",
            });
        })();
    </script>
@endpush
