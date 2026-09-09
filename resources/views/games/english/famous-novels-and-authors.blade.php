@extends('layouts.app')

@section('meta_title', 'Famous Novels & Authors — GCSE English Game')
@section('meta_blurb', 'A free GCSE English game covering well-known authors and the novels they wrote.')
@section('meta_words', 'famous novels game, famous authors game, gcse english literature game, classic novels quiz, authors and books')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-book-half',
        'title' => 'Famous Novels & Authors',
        'subtitle' => 'Read the clue, then pick the author!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Novels & authors'],
        ],
        'aboutTitle' => 'About this famous novels & authors game',
        'aboutText' => 'This free GCSE English game covers well-known novelists and playwrights often studied at GCSE, matching famous novels and plays to the authors who wrote them.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "Which author wrote 'Pride and Prejudice'?", a: "Jane Austen" },
                { q: "Which author wrote 'A Christmas Carol' and 'Great Expectations'?", a: "Charles Dickens" },
                { q: "Which author wrote 'Frankenstein'?", a: "Mary Shelley" },
                { q: "Which author wrote 'Jane Eyre'?", a: "Charlotte Bronte" },
                { q: "Which author wrote 'Wuthering Heights'?", a: "Emily Bronte" },
                { q: "Which author wrote 'To Kill a Mockingbird'?", a: "Harper Lee" },
                { q: "Which author wrote '1984' and 'Animal Farm'?", a: "George Orwell" },
                { q: "Which author wrote 'Lord of the Flies'?", a: "William Golding" },
                { q: "Which playwright wrote 'An Inspector Calls'?", a: "J.B. Priestley" },
                { q: "Which author wrote the Sherlock Holmes stories?", a: "Sir Arthur Conan Doyle" },
                { q: "Which author wrote 'The Strange Case of Dr Jekyll and Mr Hyde'?", a: "Robert Louis Stevenson" },
                { q: "Which author wrote 'Of Mice and Men'?", a: "John Steinbeck" },
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
                storageKey: 'famous-novels-and-authorsGame.settings',
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
                    return 'Think about the era the novel was written in and its most famous storyline.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a novels and authors superstar!",
            });
        })();
    </script>
@endpush
