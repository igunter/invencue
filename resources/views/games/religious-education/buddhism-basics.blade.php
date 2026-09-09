@extends('layouts.app')

@section('meta_title', 'Buddhism Basics — Religious Education Game for Kids')
@section('meta_blurb', 'A free religious education game covering the basics of Buddhism — the Buddha, the Four Noble Truths, meditation and temples.')
@section('meta_words', 'buddhism basics game, religious education game, ks3 re game, buddha four noble truths meditation quiz')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-brightness-high',
        'title' => 'Buddhism Basics',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Buddhism basics'],
        ],
        'aboutTitle' => 'About this Buddhism basics game',
        'aboutText' => 'This free religious education game covers the key beliefs, figures and practices of Buddhism — the Buddha, the Four Noble Truths, meditation and temples — described the way Buddhists understand and teach them.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'The Buddha': 'Born Siddhartha Gautama, the spiritual teacher whose teachings Buddhism is based on.',
                'Enlightenment': 'A state of complete understanding and freedom from suffering that Buddhists aim to reach.',
                'The Four Noble Truths': 'The Buddha\'s core teaching about suffering, its cause, its end, and the path that leads there.',
                'Meditation': 'A calm, focused practice of training the mind, central to Buddhist practice.',
                'Nirvana': 'The state of complete peace and freedom from suffering that Buddhists aim to reach.',
                'Vihara': 'The Buddhist word for a temple or monastery.',
                'Monk': 'A person who has given up ordinary life to follow the Buddha\'s teachings full-time.',
                'Wesak (Buddha Day)': 'A festival that celebrates the birth, enlightenment and death of the Buddha.',
                'Dharma': 'The teachings of the Buddha, and more widely the natural law of the universe.',
                'The Eightfold Path': 'A set of eight practices the Buddha taught as the way to end suffering.',
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
                storageKey: 'buddhismBasicsGame.settings',
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
                        questionText: "What is '" + term + "' in Buddhism?",
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about whether this is a person, a teaching, a practice, a place or a festival.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered the basics of Buddhism!",
            });
        })();
    </script>
@endpush
