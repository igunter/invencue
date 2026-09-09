@extends('layouts.app')

@section('meta_title', 'Islam: Beliefs & Teachings — GCSE Religious Studies Game')
@section('meta_blurb', 'A free GCSE Religious Studies game covering key Islamic beliefs and teachings — Tawhid, the Five Pillars of Islam and more.')
@section('meta_words', 'islam beliefs and teachings game, gcse religious studies game, tawhid five pillars quiz, aqa edexcel re')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-book-half',
        'title' => 'Islam: Beliefs & Teachings',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'gcse',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Beliefs & teachings'],
        ],
        'aboutTitle' => 'About this Islam: beliefs & teachings game',
        'aboutText' => 'This free GCSE Religious Studies game covers key Islamic beliefs and teachings at GCSE level — including Tawhid and the Five Pillars of Islam — described as Islamic teaching, in line with exam board specifications such as AQA and Edexcel.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Tawhid': 'The Islamic belief in the oneness of God — that there is only one God.',
                'Shahadah': 'The Islamic declaration of faith, stating belief in one God and in Muhammad as His messenger.',
                'Salah': 'The Islamic practice of praying five times a day, one of the Five Pillars of Islam.',
                'Zakah': 'The Islamic practice of giving a set portion of wealth to charity, one of the Five Pillars of Islam.',
                'Sawm': 'The Islamic practice of fasting during the month of Ramadan, one of the Five Pillars of Islam.',
                'Hajj': 'The pilgrimage to Makkah, one of the Five Pillars of Islam.',
                'Risalah': 'The Islamic belief in prophethood — that God sent prophets, including Muhammad, to guide humanity.',
                'Akhirah': 'The Islamic belief in life after death and a final judgement.',
                'Al-Qadr': 'The Islamic belief in predestination — that God has decreed what will happen.',
                'Ummah': 'The worldwide community of Muslims, united by shared faith.',
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
                storageKey: 'islamBeliefsTeachingsGame.settings',
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
                        questionText: "What does '" + term + "' mean in Islamic teaching?",
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about whether this term describes a core belief, or one of the Five Pillars of practice.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered Islam: beliefs and teachings!",
            });
        })();
    </script>
@endpush
