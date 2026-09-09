@extends('layouts.app')

@section('meta_title', 'Kindness & Shared Values — Religious Education Game for Kids')
@section('meta_blurb', 'A free religious education game for kids — learn about values like kindness, honesty and generosity that many religions share.')
@section('meta_words', 'kindness values game, religious education game for kids, ks2 re game, golden rule shared values quiz')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-heart',
        'title' => 'Kindness & Shared Values',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Shared values'],
        ],
        'aboutTitle' => 'About this kindness & shared values game',
        'aboutText' => 'This free religious education game explores values that many religions teach and share — kindness, honesty, generosity, respect and caring for others — helping young children see what different faiths have in common.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "Many religions teach a version of the 'Golden Rule'. What does it encourage people to do?", a: "Treat others the way you would like to be treated" },
                { q: "What word describes always telling the truth and not deceiving others?", a: "Honesty" },
                { q: "What word describes giving money, food or help to people in need, an important practice in many religions?", a: "Charity" },
                { q: "What word describes being kind and gentle, and caring about how others feel?", a: "Compassion" },
                { q: "What word describes treating people, places and beliefs with consideration, even if they are different from your own?", a: "Respect" },
                { q: "What word describes letting go of anger towards someone who has done wrong, and choosing not to hold it against them?", a: "Forgiveness" },
                { q: "What word describes being thankful for what you have?", a: "Gratitude" },
                { q: "What word describes not thinking too highly of yourself, and valuing other people equally?", a: "Humility" },
                { q: "What word describes giving freely to others without expecting anything back?", a: "Generosity" },
                { q: "What word describes a calm state without fighting or conflict, valued highly by most religions?", a: "Peace" },
                { q: "What word describes fairness, and making sure everyone is treated properly?", a: "Justice" },
                { q: "What word describes looking after other people, animals and the natural world?", a: "Caring" },
            ];

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

            window.ScienceQuiz.run({
                storageKey: 'kindnessSharedValuesGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const item = FACTS[randInt(0, FACTS.length - 1)];
                    return { category: type, correctText: item.a, questionText: item.q };
                },

                buildChoices: function(q) {
                    const distractors = shuffle(
                        FACTS.map(function(f) { return f.a; }).filter(function(a) { return a !== q.correctText; })
                    ).slice(0, 3);
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about how this value helps people treat each other well.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a kindness and values superstar!",
            });
        })();
    </script>
@endpush
