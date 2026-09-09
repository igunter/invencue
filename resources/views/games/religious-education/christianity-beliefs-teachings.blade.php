@extends('layouts.app')

@section('meta_title', 'Christianity: Beliefs & Teachings — GCSE Religious Studies Game')
@section('meta_blurb', 'A free GCSE Religious Studies game covering key Christian beliefs and teachings — the Trinity, Incarnation, salvation and more.')
@section('meta_words', 'christianity beliefs and teachings game, gcse religious studies game, trinity incarnation salvation quiz, aqa edexcel re')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-journal-richtext',
        'title' => 'Christianity: Beliefs & Teachings',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Beliefs & teachings'],
        ],
        'aboutTitle' => 'About this Christianity: beliefs & teachings game',
        'aboutText' => 'This free GCSE Religious Studies game covers key Christian beliefs and teachings at GCSE level — including the Trinity, the Incarnation, salvation and sin — described as Christian teaching, in line with exam board specifications such as AQA and Edexcel.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'The Trinity': 'The Christian teaching that God exists as three persons — the Father, the Son and the Holy Spirit — in one being.',
                'Incarnation': 'The Christian belief that God became human in the person of Jesus Christ.',
                'Salvation': 'The Christian belief that people are saved from sin, often understood as coming through the death and resurrection of Jesus.',
                'Omnipotent': 'A term used by Christians to describe God as all-powerful.',
                'Crucifixion': 'The method of execution by which, according to the New Testament, Jesus was put to death.',
                'Resurrection': 'The Christian belief that Jesus rose from the dead three days after his crucifixion.',
                'Sin': 'A Christian term for actions or thoughts that go against the will of God.',
                'Grace': 'The Christian belief in God\'s unearned love and forgiveness towards humanity.',
                'Sacrament': 'A religious ceremony, such as baptism or communion, seen by Christians as an outward sign of God\'s grace.',
                'The Second Coming': 'The Christian belief that Jesus will return to earth in the future.',
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
                storageKey: 'christianityBeliefsTeachingsGame.settings',
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
                        questionText: "What does '" + term + "' mean in Christian teaching?",
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about which area of Christian teaching this term belongs to — the nature of God, Jesus, or how people are saved.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered Christianity: beliefs and teachings!",
            });
        })();
    </script>
@endpush
