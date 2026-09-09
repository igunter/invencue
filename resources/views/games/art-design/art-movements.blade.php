@extends('layouts.app')

@section('meta_title', 'Art Movements — GCSE Art & Design Game')
@section('meta_blurb', 'A free GCSE Art & Design game covering major art movements in depth — Renaissance, Impressionism, Cubism, Surrealism and Pop Art.')
@section('meta_words', 'gcse art movements game, renaissance impressionism cubism surrealism pop art quiz, gcse art and design')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-hourglass-split',
        'title' => 'Art Movements',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Art movements'],
        ],
        'aboutTitle' => 'About this art movements game',
        'aboutText' => 'This free GCSE Art & Design game covers major art movements in more depth, including their approximate dates, key features and leading artists, from the Renaissance to Pop Art.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Renaissance (c.1300-1600)': 'A period reviving classical ideas, realism and perspective, led by artists like Leonardo da Vinci and Michelangelo.',
                'Impressionism (1860s-1880s)': 'A French movement using loose brushwork and light to capture fleeting moments, led by Monet and Renoir.',
                'Post-Impressionism (1880s-1900s)': 'A movement building on Impressionism with bolder colour and structure, including Van Gogh and Cézanne.',
                'Cubism (1907-1920s)': 'A movement founded by Picasso and Braque that fragmented objects into geometric planes shown from multiple angles.',
                'Surrealism (1920s-1950s)': 'A movement exploring dreams and the unconscious mind, led by artists like Dalí and Magritte.',
                'Pop Art (1950s-1960s)': 'A movement using imagery from mass media and consumer culture, led by artists like Warhol and Lichtenstein.',
                'Expressionism (early 1900s)': 'A German-led movement distorting colour and form to express raw emotion.',
                'Abstract Expressionism (1940s-1950s)': 'An American movement using large-scale, gestural, non-representational painting, led by Jackson Pollock.',
                'Fauvism (1904-1908)': 'A short-lived movement led by Matisse using intense, non-naturalistic colour.',
                'Baroque (c.1600-1750)': 'A dramatic European style using strong contrasts of light and shadow, associated with artists like Caravaggio.',
            };
            const TERM_NAMES = Object.keys(TERMS);

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
                storageKey: 'art-movementsGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const term = TERM_NAMES[randInt(0, TERM_NAMES.length - 1)];
                    return {
                        category: type,
                        label: term,
                        correctText: TERMS[term],
                        questionText: "What are the key features of " + term + "?",
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about when this movement happened, and what makes its style distinctive.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered art movements!",
            });
        })();
    </script>
@endpush
