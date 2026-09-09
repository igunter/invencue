@extends('layouts.app')

@section('meta_title', 'Sikhism Basics — Religious Education Game for Kids')
@section('meta_blurb', 'A free religious education game covering the basics of Sikhism — Guru Nanak, the Guru Granth Sahib and the gurdwara.')
@section('meta_words', 'sikhism basics game, religious education game, ks3 re game, guru nanak guru granth sahib gurdwara quiz')

@section('title', $category->name)

@section('content')
    @include('games._quiz-shell', [
        'icon' => 'bi-shield',
        'title' => 'Sikhism Basics',
        'subtitle' => 'Read the clue, then work out the answer!',
        'tier' => 'intermediate',
        'typeToggles' => [
            ['id' => 'main', 'label' => 'Sikhism basics'],
        ],
        'aboutTitle' => 'About this Sikhism basics game',
        'aboutText' => 'This free religious education game covers the key beliefs, figures and practices of Sikhism — Guru Nanak, the Guru Granth Sahib, the gurdwara and the Khalsa — described the way Sikhs understand and teach them.',
    ])
@endsection

@push('scripts')
    <script src="{{ asset('js/science-quiz.js') }}"></script>
    <script>
        (function() {
            const TERMS = {
                'Guru Nanak': 'The founder of Sikhism and the first of the ten Sikh Gurus.',
                'The Guru Granth Sahib': "Sikhism's holy book, treated as the final and eternal living Guru.",
                'Gurdwara': 'The Sikh place of worship, meaning "doorway to the Guru".',
                'The Ten Gurus': 'The ten spiritual teachers, beginning with Guru Nanak, who shaped Sikh teaching.',
                'The Khalsa': 'The community of initiated Sikhs, founded by Guru Gobind Singh.',
                'The Five Ks': 'Five physical items worn by initiated Sikhs as symbols of their faith.',
                'Langar': 'A free community meal served to everyone, regardless of religion or background, at a gurdwara.',
                'Vaisakhi': 'A festival that celebrates the founding of the Khalsa.',
                'Ik Onkar': 'A symbol and phrase meaning "there is one God", central to Sikh belief.',
                'Turban': 'A head covering worn by many Sikhs as a sign of their faith and identity.',
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
                storageKey: 'sikhismBasicsGame.settings',
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
                        questionText: "What is '" + term + "' in Sikhism?",
                    };
                },

                buildChoices: function(q) {
                    const distractors = pickOthers(TERM_NAMES, q.label, 3).map(function(t) { return TERMS[t]; });
                    return shuffle([q.correctText].concat(distractors));
                },

                hintFor: function() {
                    return 'Think about whether this is a person, a book, a building, a practice or a festival.';
                },

                explanationFor: function(q) {
                    return q.label + ': ' + q.correctText;
                },

                masteryMessage: "Amazing! You've mastered the basics of Sikhism!",
            });
        })();
    </script>
@endpush
