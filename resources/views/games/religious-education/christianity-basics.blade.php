@extends('layouts.app')

@section('meta_title', 'Christianity Basics — Religious Education Game for Kids')
@section('meta_blurb', 'A free religious education game covering the basics of Christianity — God, Jesus, the Bible, the church and key festivals.')
@section('meta_words', 'christianity basics game, religious education game, ks3 re game, jesus bible church quiz')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-journal-bookmark',
        'title' => 'Christianity Basics',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Christianity basics'],
        ],
        'aboutTitle' => 'About this Christianity basics game',
        'aboutText' => 'This free religious education game covers the key beliefs, figures and practices of Christianity — God, Jesus, the Bible, the church, baptism and major festivals — described the way Christians understand and teach them.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'God': 'The being Christians worship and believe created and rules over the universe.',
                'Jesus Christ': 'The central figure of Christianity, believed by Christians to be the son of God.',
                'The Bible': "Christianity's holy book, made up of the Old Testament and the New Testament.",
                'Church': 'A building where Christians gather to worship, and also the word for the wider Christian community.',
                'The Ten Commandments': 'A set of rules that, according to the Old Testament, God gave to Moses to guide how people should live.',
                'Baptism': 'A ceremony using water that welcomes a person into the Christian faith.',
                'The Lord\'s Prayer': 'A well-known prayer that, according to the New Testament, Jesus taught to his followers.',
                'The Disciples': 'The twelve close followers who, according to the New Testament, travelled with Jesus and learned from him.',
                'Christmas': 'The Christian festival that celebrates the birth of Jesus.',
                'Easter': 'The Christian festival that celebrates the death and resurrection of Jesus.',
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
                storageKey: 'christianityBasicsGame.settings',
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
                        questionText: "What is '" + term + "' in Christianity?",
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about whether this is a person, a book, a building, a ceremony or a festival.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered the basics of Christianity!",
            });
        })();
    </script>
@endpush
