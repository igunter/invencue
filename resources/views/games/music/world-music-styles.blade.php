@extends('layouts.app')

@section('meta_title', 'World Music Styles — GCSE Music Game')
@section('meta_blurb', 'A free GCSE music game covering musical traditions and styles from around the world, including Indian classical, gamelan, reggae and samba.')
@section('meta_words', 'gcse world music game, indian classical gamelan reggae samba, world music styles quiz, gcse music revision')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-globe2',
        'title' => 'World Music Styles',
        'subtitle' => 'Read the clue, then work out the style!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'World music facts'],
        ],
        'aboutTitle' => 'About this world music styles game',
        'aboutText' => 'This free GCSE music game covers musical traditions and styles from around the world, including Indian classical music, gamelan, reggae, samba and other world music traditions studied at GCSE.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const FACTS = [
                { q: "Which style of Indian classical music uses a raga (melodic framework) and tala (rhythmic cycle)?", a: "Indian classical music" },
                { q: "Which Indonesian ensemble music is based around tuned gongs, metallophones and drums?", a: "Gamelan" },
                { q: "Which Jamaican style features off-beat guitar and keyboard chords and a laid-back groove?", a: "Reggae" },
                { q: "Which Brazilian style, associated with Carnival, features driving percussion and call-and-response?", a: "Samba" },
                { q: "Which West African drumming tradition often uses interlocking polyrhythms played on djembes?", a: "West African drumming" },
                { q: "Which stringed instrument, with a long neck and movable frets, is central to Indian classical music?", a: "The sitar" },
                { q: "Which pair of hand drums is commonly used to accompany Indian classical music?", a: "Tabla" },
                { q: "Which Cuban style blends African rhythms with Spanish musical influences, using instruments like claves?", a: "Salsa (Afro-Cuban music)" },
                { q: "Which Argentinian dance style is known for its dramatic, passionate music, often featuring the bandoneon?", a: "Tango" },
                { q: "Which Celtic folk tradition features fiddles, tin whistles and lively jigs and reels?", a: "Irish folk music" },
                { q: "Which Australian Aboriginal wind instrument produces a continuous drone using circular breathing?", a: "The didgeridoo" },
                { q: "Which North African and Middle Eastern rhythmic mode system is comparable to the Indian raga concept?", a: "Maqam" },
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
                storageKey: 'worldMusicStylesGame.settings',
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
                    return 'Think about which country or region this tradition comes from, and the instruments involved.';
                },

                explanationFor: function(q) {
                    return q.correctText + '.';
                },

                masteryMessage: "Amazing! You're a world music styles superstar!",
            });
        })();
    </script>
@endpush
