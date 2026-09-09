@extends('layouts.app')

@section('meta_title', 'Musical Instrument Families — Music Game for Kids')
@section('meta_blurb', 'A free music game — learn the strings, woodwind, brass and percussion families in more depth, with specific instrument examples.')
@section('meta_words', 'instrument families game, strings woodwind brass percussion, orchestra instruments quiz, ks3 music game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-diagram-3',
        'title' => 'Musical Instrument Families',
        'subtitle' => 'Pick your question types, then test your knowledge!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'family', 'label' => 'Which family?'],
            ['id' => 'facts', 'label' => 'Instrument facts'],
        ],
        'aboutTitle' => 'About this musical instrument families game',
        'aboutText' => 'This free music game builds on the basics, exploring the strings, woodwind, brass and percussion families in more depth with specific instrument examples and interesting facts. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FAMILY = {
                'Violin': 'Strings',
                'Viola': 'Strings',
                'Double bass': 'Strings',
                'Harp': 'Strings',
                'Oboe': 'Woodwind',
                'Bassoon': 'Woodwind',
                'Piccolo': 'Woodwind',
                'Saxophone': 'Woodwind',
                'Trombone': 'Brass',
                'Tuba': 'Brass',
                'Cornet': 'Brass',
                'French horn': 'Brass',
                'Timpani': 'Percussion',
                'Snare drum': 'Percussion',
                'Cymbals': 'Percussion',
                'Glockenspiel': 'Percussion',
            };
            const FAMILY_NAMES = Object.keys(FAMILY);
            const OPTIONS = ['Strings', 'Woodwind', 'Brass', 'Percussion'];

            const FACTS = [
                { q: "Which brass instrument has a slide instead of valves to change pitch?", a: "The trombone" },
                { q: "Which percussion instrument consists of large tuned copper bowls, played with mallets?", a: "Timpani" },
                { q: "Which woodwind instrument is the smallest and highest-pitched, a tiny version of the flute?", a: "The piccolo" },
                { q: "Which brass instrument is played with a hand inside the bell to change its tone?", a: "The French horn" },
                { q: "Which string instrument is plucked with the fingers and has a large triangular frame?", a: "The harp" },
                { q: "Which woodwind instrument uses a single reed and comes in many sizes, common in jazz?", a: "The saxophone" },
                { q: "Which percussion instrument is a metal frame with tuned bars, played like a small xylophone?", a: "The glockenspiel" },
                { q: "Which brass instrument is the largest and lowest-pitched, often playing the bass line?", a: "The tuba" },
                { q: "Which string instrument is held under the chin and is smaller than a viola?", a: "The violin" },
                { q: "Which percussion instrument is a pair of metal discs that are clashed together?", a: "Cymbals" },
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
                storageKey: 'musicalInstrumentFamiliesGame.settings',
                types: ['family', 'facts'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'family') {
                        const instrument = FAMILY_NAMES[randInt(0, FAMILY_NAMES.length - 1)];
                        return {
                            category: type,
                            correctText: FAMILY[instrument],
                            questionText: instrument + ' — which family does it belong to?',
                        };
                    }
                    const item = FACTS[randInt(0, FACTS.length - 1)];
                    return { category: type, correctText: item.a, questionText: item.q };
                },

                buildChoices: function(q) {
                    if (q.category === 'family') {
                        return shuffle(OPTIONS.slice());
                    }
                    const distractors = shuffle(
                        FACTS.map(function(f) { return f.a; }).filter(function(a) { return a !== q.correctText; })
                    ).slice(0, 3);
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'family') {
                        return 'Is it bowed or plucked, blown through a reed, buzzed through a metal mouthpiece, or hit?';
                    }
                    return 'Think about how the instrument is played and where it sits in its family.';
                },

                explanationFor: function(q) {
                    if (q.category === 'family') return q.correctText + ' family.';
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're an instrument families superstar!",
            });
        })();
    </script>
@endpush
