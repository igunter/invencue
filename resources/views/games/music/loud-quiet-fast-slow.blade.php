@extends('layouts.app')

@section('meta_title', 'Loud & Quiet, Fast & Slow — Music Game for Kids')
@section('meta_blurb', 'A free music game for young kids — learn simple dynamics (loud/quiet) and tempo (fast/slow) words used in music.')
@section('meta_words', 'loud quiet music game, fast slow music game, dynamics tempo for kids, music vocabulary game, ks1 ks2 music game')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-volume-up',
        'title' => 'Loud & Quiet, Fast & Slow',
        'subtitle' => 'Pick your question types, then match the music word!',
        'tier' => 'basic',
        'typeToggles' => [
            ['id' => 'dynamics', 'label' => 'Loud or quiet?'],
            ['id' => 'tempo', 'label' => 'Fast or slow?'],
        ],
        'aboutTitle' => 'About this loud & quiet, fast & slow game',
        'aboutText' => 'This free music game helps young kids learn simple words used to describe volume (dynamics) and speed (tempo) in music, from loud and quiet to fast and slow. Choose which question types to include, set your question count and time limit, then see how many you can get right.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const DYNAMICS = {
                'Loud': 'Music played strongly, so it can be clearly heard.',
                'Quiet': 'Music played softly, like a lullaby.',
                'Forte': 'The music word for loud.',
                'Piano (as a music word)': 'The music word for quiet, soft.',
                'Crescendo': 'A gradual increase from quiet to loud.',
                'Diminuendo': 'A gradual decrease from loud to quiet.',
                'Getting louder': 'When the music gradually increases in volume.',
                'Getting quieter': 'When the music gradually decreases in volume.',
                'A whisper of sound': 'Music played extremely quietly.',
                'A blast of sound': 'Music played extremely loudly.',
            };
            const DYNAMICS_NAMES = Object.keys(DYNAMICS);

            const TEMPO = {
                'Fast': 'Music played quickly.',
                'Slow': 'Music played unhurriedly, with space between the notes.',
                'Allegro': 'The music word for fast and lively.',
                'Adagio': 'The music word for slow and at ease.',
                'Getting faster': 'When the music gradually speeds up.',
                'Getting slower': 'When the music gradually slows down.',
                'Accelerando': 'A term meaning the music gradually speeds up.',
                'Ritardando': 'A term meaning the music gradually slows down.',
                'A steady walking speed': 'A comfortable, walking-pace tempo.',
                'A gallop': 'A very fast, energetic tempo, like a horse running.',
            };
            const TEMPO_NAMES = Object.keys(TEMPO);

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
                storageKey: 'loudQuietFastSlowGame.settings',
                types: ['dynamics', 'tempo'],
                defaultQuestionCount: 10,
                defaultTimeLimit: 20,
                gridColClass: 'col-12',

                buildQuestion: function(type) {
                    if (type === 'dynamics') {
                        const term = DYNAMICS_NAMES[randInt(0, DYNAMICS_NAMES.length - 1)];
                        return {
                            category: type,
                            label: term,
                            correctText: DYNAMICS[term],
                            questionText: "What does '" + term + "' mean?",
                        };
                    }
                    const term = TEMPO_NAMES[randInt(0, TEMPO_NAMES.length - 1)];
                    return {
                        category: type,
                        label: term,
                        correctText: TEMPO[term],
                        questionText: "What does '" + term + "' mean?",
                    };
                },

                buildChoices: function(q) {
                    if (q.category === 'dynamics') {
                        const distractors = pickOthers(DYNAMICS_NAMES, q.label, 3).map(function(t) { return DYNAMICS[t]; });
                        return shuffle([q.correctText].concat(distractors));
                    }
                    const distractors = pickOthers(TEMPO_NAMES, q.label, 3).map(function(t) { return TEMPO[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function(q) {
                    if (q.category === 'dynamics') {
                        return 'Think about how loud or quiet the music is, or whether it is changing.';
                    }
                    return 'Think about how fast or slow the music is, or whether it is changing speed.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You're a loud, quiet, fast and slow superstar!",
            });
        })();
    </script>
@endpush
