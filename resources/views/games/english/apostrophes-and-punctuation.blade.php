@extends('layouts.app')

@section('meta_title', 'Apostrophes & Punctuation — English Game for Kids')
@section('meta_blurb', 'A free English game for kids — decide whether an apostrophe shows possession or a contraction.')
@section('meta_words', 'apostrophes game, possession contraction game, punctuation game for kids, ks2 english game, ks3 english game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-quote',
        'title' => 'Apostrophes & Punctuation',
        'subtitle' => 'Read the phrase, then say what the apostrophe is doing!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Possession or contraction?'],
        ],
        'aboutTitle' => 'About this apostrophes & punctuation game',
        'aboutText' => 'This free English game tests one of the trickiest bits of punctuation — apostrophes. Learn to tell the difference between an apostrophe showing possession (something belongs to someone) and one showing a contraction (two words joined together).',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const PHRASES = {
                "The dog's bone": 'Possession',
                "It's raining": 'Contraction',
                "Sam's book": 'Possession',
                "Don't go": 'Contraction',
                "The teacher's desk": 'Possession',
                "They're happy": 'Contraction',
                "The children's toys": 'Possession',
                "I'm tired": 'Contraction',
                "The cat's tail": 'Possession',
                "Can't stop": 'Contraction',
                "The girls' coats": 'Possession',
                "We'll see": 'Contraction',
            };
            const PHRASE_NAMES = Object.keys(PHRASES);

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
                storageKey: 'apostrophes-and-punctuationGame.settings',
                types: ['main'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    const phrase = PHRASE_NAMES[randInt(0, PHRASE_NAMES.length - 1)];
                    return {
                        category: type,
                        correctText: PHRASES[phrase],
                        questionText: "Does the apostrophe in '" + phrase + "' show possession or a contraction?",
                    };
                },

                buildChoices: function() {
                    return shuffle(['Possession', 'Contraction']);
                },

                hintFor: function() {
                    return 'Could you replace the apostrophe with two separate words? If so, it\'s a contraction. If something belongs to someone, it\'s possession.';
                },

                explanationFor: function(q) {
                    return q.correctText === 'Possession'
                        ? 'The apostrophe shows that something belongs to someone.'
                        : 'The apostrophe shows two words have been joined together, with letters missed out.';
                },

                masteryMessage: "Amazing! You're an apostrophes superstar!",
            });
        })();
    </script>
@endpush
